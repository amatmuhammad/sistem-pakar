# Perhitungan Akurasi Metode CBR dan CF

Dokumen ini menjelaskan cara menghitung akurasi masing-masing metode dalam sistem pakar CBR-CF, beserta kriteria penentuan kasus akurat.

---

## 1. Rumus Akurasi

```
Persentase Akurasi (%) = (Jumlah Kasus Akurat ÷ Jumlah Kasus yang Ada) × 100%
```

### 1.1 Akurasi Metode CBR

```
Akurasi CBR = (Jumlah hasil CBR yang benar ÷ Total kasus CBR) × 100%
```

### 1.2 Akurasi Metode CF

```
Akurasi CF = (Jumlah hasil CF yang benar ÷ Total kasus CF) × 100%
```

---

## 2. Kriteria "Kasus Akurat"

Sebuah kasus dianggap **akurat** apabila:

```
Penyakit hasil diagnosa sistem == Diagnosis sebenarnya (ground truth / diagnosis pakar)
```

Di sistem ini, kebenaran hasil dapat ditentukan melalui:

| Metode Penentuan | Kolom / Cara | Keterangan |
|------------------|--------------|------------|
| Validasi pakar | `divalidasi_pakar = true` pada `HasilDiagnosisCbr` | Pakar menandai hasil diagnosa sesuai dengan diagnosis sebenarnya |
| Perbandingan CBR vs CF | Hasil penyakit CBR sama dengan hasil penyakit CF | Untuk kasus dengan input gejala yang sama, kedua metode menyimpulkan penyakit yang sama |

---

## 3. Status Data Saat Ini (Setelah Penghapusan)

Data riwayat diagnosa telah dihapus dari database:

| Tabel | Jumlah Data | Keterangan |
|-------|-------------|------------|
| `hasil_diagnosis_cbr` | 0 | Sudah dihapus melalui tinker |
| `hasil_diagnosis_cf` | 0 | Sudah dihapus melalui tinker |

Karena tidak ada data, **akurasi saat ini = 0%** (tidak dapat dihitung) untuk kedua metode.

Untuk menghitung akurasi yang sesungguhnya, diperlukan:
1. Input data diagnosa baru melalui halaman diagnosa
2. Validasi pakar pada hasil diagnosa (`divalidasi_pakar = true`)
3. Bandingkan hasil diagnosa dengan diagnosis sebenarnya (ground truth)

---

## 4. Contoh Perhitungan

### Contoh 1: Akurasi CBR

| Total Kasus CBR | Kasus Akurat (divalidasi benar) | Perhitungan | Akurasi |
|-----------------|---------------------------------|-------------|---------|
| 50 | 40 | (40 ÷ 50) × 100% | **80%** |

### Contoh 2: Akurasi CF

| Total Kasus CF | Kasus Akurat (divalidasi benar) | Perhitungan | Akurasi |
|----------------|---------------------------------|-------------|---------|
| 50 | 45 | (45 ÷ 50) × 100% | **90%** |

---

## 5. Tabel Rekapitulasi (Template)

| Metode | Jumlah Kasus | Kasus Akurat | Akurasi |
|--------|-------------|--------------|---------|
| CBR | 0 | 0 | 0% |
| CF | 0 | 0 | 0% |

*Isi tabel di atas setelah data diagnosis baru dimasukkan dan divalidasi.*

---

## 6. Cara Cek Akurasi Melalui Tinker

```php
use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;

// Akurasi CBR
$totalCbr = HasilDiagnosisCbr::count();
$benarCbr = HasilDiagnosisCbr::where('divalidasi_pakar', true)->count();
$akurasiCbr = $totalCbr > 0 ? round(($benarCbr / $totalCbr) * 100, 2) : 0;

// Akurasi CF (gunakan validasi pakar atau ground truth lain)
$totalCf = HasilDiagnosisCf::count();
$benarCf = 0; // sesuaikan dengan kriteria akurat yang Anda tentukan
$akurasiCf = $totalCf > 0 ? round(($benarCf / $totalCf) * 100, 2) : 0;

echo "CBR: $totalCbr kasus, $benarCbr benar, akurasi $akurasiCbr%\n";
echo "CF: $totalCf kasus, $benarCf benar, akurasi $akurasiCf%\n";
```

---

## 7. Catatan Penting

- Akurasi hanya bermakna jika dihitung terhadap jumlah kasus yang sama dan kriteria kebenaran yang konsisten untuk kedua metode.
- Kolom `divalidasi_pakar` pada tabel `hasil_diagnosis_cbr` masih bernilai `false` untuk semua kasus (belum ada validasi pakar).
- Untuk akurasi yang akurat, setiap hasil diagnosa perlu divalidasi oleh pakar sesuai label penyakit sebenarnya sebelum dihitung dengan rumus di atas.

---