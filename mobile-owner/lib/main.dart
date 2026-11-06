import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

const petrol = Color(0xFF0B5563);
const ink = Color(0xFF1C1B17);
const canvas = Color(0xFFF6F1E7);

void main() {
  runApp(const OwnerApp());
}

class OwnerApp extends StatelessWidget {
  const OwnerApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Kasir Pintar Owner',
      theme: ThemeData(
        useMaterial3: true,
        scaffoldBackgroundColor: canvas,
        colorScheme: ColorScheme.fromSeed(
          seedColor: petrol,
          brightness: Brightness.light,
        ),
        fontFamily: 'sans',
      ),
      home: const OwnerGate(),
    );
  }
}

// ============================================================
// API
// ============================================================

class Api {
  // Untuk HP fisik, API_BASE diberikan saat menjalankan Flutter:
  //
  // flutter run --dart-define=API_BASE=http://10.112.84.151:8000/api/mobile
  //
  // Untuk Android Emulator, default 10.0.2.2 digunakan.

  static const String base = String.fromEnvironment(
    'API_BASE',
    defaultValue: 'http://10.0.2.2:8000/api/mobile',
  );

  Future<String?> token() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('owner_token');
  }

  Future<Map<String, dynamic>> login(
    String username,
    String password,
  ) async {
    final response = await http.post(
      Uri.parse('$base/login'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'username': username,
        'password': password,
      }),
    );

    final data = jsonDecode(response.body);

    if (response.statusCode >= 400) {
      throw Exception(
        data['message'] ?? 'Login gagal',
      );
    }

    final prefs = await SharedPreferences.getInstance();

    await prefs.setString(
      'owner_token',
      data['token'],
    );

    return data;
  }

  Future<Map<String, dynamic>> dashboard() async {
    final tokenValue = await token();

    final response = await http.get(
      Uri.parse('$base/dashboard'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $tokenValue',
      },
    );

    if (response.statusCode == 401) {
      throw const Unauthorized();
    }

    if (response.statusCode >= 400) {
      throw Exception(
        'Data dashboard gagal dimuat',
      );
    }

    return jsonDecode(response.body);
  }

  Future<void> logout() async {
    final tokenValue = await token();

    if (tokenValue != null) {
      await http.post(
        Uri.parse('$base/logout'),
        headers: {
          'Authorization': 'Bearer $tokenValue',
          'Accept': 'application/json',
        },
      );
    }

    final prefs = await SharedPreferences.getInstance();

    await prefs.remove('owner_token');
  }
}

class Unauthorized implements Exception {
  const Unauthorized();
}

// ============================================================
// OWNER GATE
// ============================================================

class OwnerGate extends StatefulWidget {
  const OwnerGate({super.key});

  @override
  State<OwnerGate> createState() => _OwnerGateState();
}

class _OwnerGateState extends State<OwnerGate> {
  final Api api = Api();

  bool loading = true;
  String? tokenValue;

  @override
  void initState() {
    super.initState();
    checkLogin();
  }

  Future<void> checkLogin() async {
    final token = await api.token();

    if (!mounted) return;

    setState(() {
      tokenValue = token;
      loading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (loading) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      );
    }

    if (tokenValue == null) {
      return LoginPage(api: api);
    }

    return DashboardPage(api: api);
  }
}

// ============================================================
// LOGIN
// ============================================================

class LoginPage extends StatefulWidget {
  final Api api;

  const LoginPage({
    super.key,
    required this.api,
  });

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final usernameController = TextEditingController(
    text: 'owner',
  );

  final passwordController = TextEditingController(
    text: 'password',
  );

  bool busy = false;
  String? error;

  @override
  void dispose() {
    usernameController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  Future<void> login() async {
    setState(() {
      busy = true;
      error = null;
    });

    try {
      await widget.api.login(
        usernameController.text.trim(),
        passwordController.text,
      );

      if (!mounted) return;

      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (_) => DashboardPage(
            api: widget.api,
          ),
        ),
      );
    } catch (e) {
      if (!mounted) return;

      setState(() {
        error = e
            .toString()
            .replaceFirst('Exception: ', '');
      });
    } finally {
      if (!mounted) return;

      setState(() {
        busy = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: ConstrainedBox(
            constraints: const BoxConstraints(
              minHeight: 650,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 80),

                const Text(
                  'KASIR PINTAR',
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 2,
                    color: petrol,
                  ),
                ),

                const SizedBox(height: 8),

                const Text(
                  'Dashboard Owner',
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.w700,
                    color: ink,
                  ),
                ),

                const SizedBox(height: 8),

                const Text(
                  'Pantau usaha tanpa harus berada di meja kasir.',
                  style: TextStyle(
                    color: Colors.black54,
                    fontSize: 15,
                  ),
                ),

                const SizedBox(height: 32),

                TextField(
                  controller: usernameController,
                  decoration: const InputDecoration(
                    labelText: 'Username',
                    border: OutlineInputBorder(),
                  ),
                ),

                const SizedBox(height: 14),

                TextField(
                  controller: passwordController,
                  obscureText: true,
                  decoration: const InputDecoration(
                    labelText: 'Password',
                    border: OutlineInputBorder(),
                  ),
                ),

                if (error != null) ...[
                  const SizedBox(height: 12),
                  Text(
                    error!,
                    style: const TextStyle(
                      color: Colors.red,
                    ),
                  ),
                ],

                const SizedBox(height: 20),

                SizedBox(
                  width: double.infinity,
                  height: 52,
                  child: FilledButton(
                    onPressed: busy ? null : login,
                    child: Text(
                      busy
                          ? 'Menghubungkan...'
                          : 'Masuk',
                    ),
                  ),
                ),

                const SizedBox(height: 40),

                const Text(
                  'Dashboard ini digunakan khusus oleh Owner '
                  'untuk monitoring usaha.',
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.black45,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

// ============================================================
// DASHBOARD
// ============================================================

class DashboardPage extends StatefulWidget {
  final Api api;

  const DashboardPage({
    super.key,
    required this.api,
  });

  @override
  State<DashboardPage> createState() =>
      _DashboardPageState();
}

class _DashboardPageState
    extends State<DashboardPage> {
  late Future<Map<String, dynamic>> future;

  @override
  void initState() {
    super.initState();

    future = widget.api.dashboard();
  }

  String money(dynamic value) {
    final number = value is num
        ? value.toInt()
        : 0;

    final formatted = number
        .toString()
        .replaceAllMapped(
          RegExp(r'(?=(\d{3})+(?!\d))'),
          (_) => '.',
        );

    return 'Rp $formatted';
  }

  Future<void> refreshDashboard() async {
    setState(() {
      future = widget.api.dashboard();
    });

    await future;
  }

  Future<void> logout() async {
    await widget.api.logout();

    if (!mounted) return;

    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(
        builder: (_) => LoginPage(
          api: widget.api,
        ),
      ),
      (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Ringkasan Owner',
          style: TextStyle(
            fontWeight: FontWeight.w700,
          ),
        ),
        actions: [
          IconButton(
            tooltip: 'Keluar',
            onPressed: logout,
            icon: const Icon(
              Icons.logout_rounded,
            ),
          ),
        ],
      ),

      body: FutureBuilder<Map<String, dynamic>>(
        future: future,
        builder: (context, snapshot) {
          if (snapshot.connectionState !=
              ConnectionState.done) {
            return const Center(
              child: CircularProgressIndicator(),
            );
          }

          if (snapshot.hasError) {
            return ErrorState(
              error: snapshot.error,
              onRetry: refreshDashboard,
            );
          }

          final data = snapshot.data;

          if (data == null) {
            return ErrorState(
              error: 'Data dashboard kosong.',
              onRetry: refreshDashboard,
            );
          }

          return DashboardContent(
            data: data,
            money: money,
            onRefresh: refreshDashboard,
          );
        },
      ),
    );
  }
}

// ============================================================
// DASHBOARD CONTENT
// ============================================================

class DashboardContent extends StatelessWidget {
  final Map<String, dynamic> data;
  final String Function(dynamic) money;
  final Future<void> Function() onRefresh;

  const DashboardContent({
    super.key,
    required this.data,
    required this.money,
    required this.onRefresh,
  });

  @override
  Widget build(BuildContext context) {
    final sales =
        Map<String, dynamic>.from(
      data['sales'] ?? {},
    );

    final finance =
        Map<String, dynamic>.from(
      data['finance'] ?? {},
    );

    final lowStock =
        List<dynamic>.from(
      data['low_stock'] ?? [],
    );

    final restock =
        List<dynamic>.from(
      data['restock'] ?? [],
    );

    return RefreshIndicator(
      onRefresh: onRefresh,
      child: ListView(
        physics:
            const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(
          16,
          8,
          16,
          32,
        ),
        children: [
          const Text(
            'MONITORING USAHA',
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.bold,
              letterSpacing: 1.6,
              color: petrol,
            ),
          ),

          const SizedBox(height: 4),

          const Text(
            'Hari ini, keputusan lebih ringan.',
            style: TextStyle(
              fontSize: 25,
              fontWeight: FontWeight.w700,
              color: ink,
            ),
          ),

          const SizedBox(height: 18),

          // =========================
          // METRIC
          // =========================

          Row(
            crossAxisAlignment:
                CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Metric(
                  label: 'Penjualan',
                  value: money(
                    sales['total'],
                  ),
                  note:
                      '${sales['transactions'] ?? 0} transaksi',
                ),
              ),

              const SizedBox(width: 10),

              Expanded(
                child: Metric(
                  label: 'Arus kas',
                  value: money(
                    finance['net'],
                  ),
                  note: 'bersih bulan ini',
                ),
              ),
            ],
          ),

          const SizedBox(height: 20),

          // =========================
          // KEUANGAN
          // =========================

          Section(
            title: 'Keuangan',
            child: Row(
              children: [
                Expanded(
                  child: Finance(
                    label: 'Masuk',
                    value: money(
                      finance['income'],
                    ),
                  ),
                ),
                Expanded(
                  child: Finance(
                    label: 'Keluar',
                    value: money(
                      finance['expense'],
                    ),
                  ),
                ),
              ],
            ),
          ),

          // =========================
          // STOK RENDAH
          // =========================

          Section(
            title: 'Stok rendah',
            child: lowStock.isEmpty
                ? const Text(
                    'Semua stok dalam batas aman.',
                    style: TextStyle(
                      color: Colors.black54,
                    ),
                  )
                : Column(
                    children: lowStock
                        .map(
                          (item) {
                            final product =
                                Map<String, dynamic>.from(
                              item,
                            );

                            return ListTile(
                              contentPadding:
                                  EdgeInsets.zero,
                              leading: const CircleAvatar(
                                backgroundColor:
                                    Color(0xFFF4E8D4),
                                child: Icon(
                                  Icons.inventory_2_outlined,
                                  color:
                                      Color(0xFFB87400),
                                ),
                              ),
                              title: Text(
                                product['name']
                                        ?.toString() ??
                                    '-',
                                style: const TextStyle(
                                  fontWeight:
                                      FontWeight.w600,
                                ),
                              ),
                              subtitle: Text(
                                '${product['stock'] ?? 0} / '
                                'minimum ${product['minimum'] ?? 0}',
                              ),
                              trailing:
                                  const Text(
                                'Menipis',
                                style: TextStyle(
                                  color:
                                      Color(0xFFB87400),
                                  fontWeight:
                                      FontWeight.w600,
                                ),
                              ),
                            );
                          },
                        )
                        .toList(),
                  ),
          ),

          // =========================
          // REKOMENDASI RESTOCK
          // =========================

          Section(
            title: 'Rekomendasi restock',
            child: restock.isEmpty
                ? const Text(
                    'Belum ada rekomendasi.',
                    style: TextStyle(
                      color: Colors.black54,
                    ),
                  )
                : Column(
                    children: restock
                        .map(
                          (item) {
                            final product =
                                Map<String, dynamic>.from(
                              item,
                            );

                            final quantity =
                                product[
                                    'recommended_quantity'];

                            final quantityNumber =
                                quantity is num
                                    ? quantity.toInt()
                                    : 0;

                            return ListTile(
                              contentPadding:
                                  EdgeInsets.zero,
                              leading: const CircleAvatar(
                                backgroundColor:
                                    Color(0xFFE2F0EE),
                                child: Icon(
                                  Icons
                                      .shopping_cart_checkout,
                                  color: petrol,
                                ),
                              ),
                              title: Text(
                                product['product']
                                        ?.toString() ??
                                    '-',
                                style: const TextStyle(
                                  fontWeight:
                                      FontWeight.w600,
                                ),
                              ),
                              subtitle: Text(
                                'Stok saat ini: '
                                '${product['current_stock'] ?? 0}',
                              ),
                              trailing: Text(
                                '+$quantityNumber pcs',
                                style: const TextStyle(
                                  color: petrol,
                                  fontWeight:
                                      FontWeight.bold,
                                ),
                              ),
                            );
                          },
                        )
                        .toList(),
                  ),
          ),
        ],
      ),
    );
  }
}

// ============================================================
// ERROR STATE
// ============================================================

class ErrorState extends StatelessWidget {
  final Object? error;
  final Future<void> Function() onRetry;

  const ErrorState({
    super.key,
    required this.error,
    required this.onRetry,
  });

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(
              Icons.cloud_off_rounded,
              size: 52,
              color: Colors.black38,
            ),

            const SizedBox(height: 16),

            const Text(
              'Dashboard tidak dapat dimuat',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w700,
              ),
            ),

            const SizedBox(height: 8),

            Text(
              error?.toString() ??
                  'Terjadi kesalahan.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Colors.black54,
              ),
            ),

            const SizedBox(height: 20),

            FilledButton.icon(
              onPressed: onRetry,
              icon: const Icon(
                Icons.refresh_rounded,
              ),
              label: const Text(
                'Coba lagi',
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ============================================================
// METRIC CARD
// ============================================================

class Metric extends StatelessWidget {
  final String label;
  final String value;
  final String note;

  const Metric({
    super.key,
    required this.label,
    required this.value,
    required this.note,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(
          color: const Color(0xFFE7E1D3),
        ),
        borderRadius: BorderRadius.circular(14),
      ),
      child: Column(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              color: Colors.black54,
              fontSize: 13,
            ),
          ),

          const SizedBox(height: 7),

          FittedBox(
            fit: BoxFit.scaleDown,
            alignment: Alignment.centerLeft,
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.w700,
                fontFamily: 'monospace',
              ),
            ),
          ),

          const SizedBox(height: 4),

          Text(
            note,
            style: const TextStyle(
              fontSize: 12,
              color: Colors.black45,
            ),
          ),
        ],
      ),
    );
  }
}

// ============================================================
// FINANCE
// ============================================================

class Finance extends StatelessWidget {
  final String label;
  final String value;

  const Finance({
    super.key,
    required this.label,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(
        vertical: 10,
        horizontal: 4,
      ),
      child: Column(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              color: Colors.black54,
            ),
          ),

          const SizedBox(height: 4),

          FittedBox(
            fit: BoxFit.scaleDown,
            alignment: Alignment.centerLeft,
            child: Text(
              value,
              style: const TextStyle(
                fontFamily: 'monospace',
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ============================================================
// SECTION
// ============================================================

class Section extends StatelessWidget {
  final String title;
  final Widget child;

  const Section({
    super.key,
    required this.title,
    required this.child,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(
        bottom: 18,
      ),
      padding: const EdgeInsets.only(
        top: 12,
      ),
      decoration: const BoxDecoration(
        border: Border(
          top: BorderSide(
            color: ink,
            width: 2,
          ),
        ),
      ),
      child: Column(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w700,
            ),
          ),

          const SizedBox(height: 6),

          child,
        ],
      ),
    );
  }
}