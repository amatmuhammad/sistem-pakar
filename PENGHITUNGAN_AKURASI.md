# Perhitungan Akurasi Metode CBR dan CF

## Rumus

```
Persentase Akurasi (%) = (Jumlah Kasus Akurat ÷ Jumlah Kasus yang Ada) × 100%
```

**Keterangan:**
- **Jumlah Kasus Akurat** = jumlah kasus diagnosa yang hasilnya **sesuai/benar** (hasil diagnosa sistem sama dengan diagnosis pakar/keadaan sebenarnya).
- **Jumlah Kasus yang Ada** = total seluruh kasus diagnosa yang dianalisis.

---

## Penerapan per Metode

### 1. Akurasi Metode CBR

```
Akurasi CBR = (Jumlah hasil CBR yang benar ÷ Total kasus CBR) × 100%
```

Contoh: dari 50 kasus CBR, 40 di antaranya menghasilkan penyakit yang sesuai dengan ground truth (validasi pakar), maka:

```
Akurasi CBR = (40 ÷ 50) × 100% = 80%
```

### 2. Akurasi Metode CF

```
Akurasi CF = (Jumlah hasil CF yang benar ÷ Total kasus CF) × 100%
```

Contoh: dari 50 kasus CF, 45 di antaranya menghasilkan penyakit yang sesuai, maka:

```
Akurasi CF = (45 ÷ 50) × 100% = 90%
```

---

## Kriteria "Kasus Akurat"

Sebuah kasus dianggap **akurat** apabila:

```
Penyakit hasil diagnosa sistem == Diagnosis sebenarnya (ground truth / diagnosis pakar)
```

Di sistem ini, kebenaran hasil dapat ditentukan melalui validasi pakar (`divalidasi_pakar` pada hasil diagnosa CBR) atau perbandingan dengan label diagnosis yang sudah diketahui dari dataset kasus.

| Status | Keterangan |
|--------|------------|
| ✓ Akurat | Hasil diagnosa sistem sesuai dengan diagnosis sebenarnya |
| ✗ Tidak akurat | Hasil diagnosa sistem berbeda atau tidak ditemukan penyakit |

---

## Tabel Rekapitulasi (Contoh)

| Metode | Jumlah Kasus | Kasus Akurat | Akurasi |
|--------|-------------|--------------|---------|
| CBR | 50 | 40 | 80% |
| CF | 50 | 45 | 90% |

---

## Catatan

- Semakin **tinggi** persentase akurasi, semakin baik metode tersebut dalam mendiagnosa.
- Akurasi hanya bermakna jika dihitung terhadap **jumlah kasus yang sama** dan **kriteria kebenaran yang konsisten** untuk kedua metode.
- Perbandingan akurasi CBR vs CF menunjukkan metode mana yang lebih direkomendasikan untuk permasalahan ini.

*Dokumen ini hanya dokumentasi penjelasan, tidak ada perubahan kode.*

## Data Aktual pada Database (per 06 Okt 2026)

Data kasus dihitung langsung dari database `sisitem-pakar-cbr`:

| Data | Jumlah |
|------|--------|
| Total hasil diagnosa CBR (`hasil_diagnosis_cbr`) | 16 kasus |
| Total hasil diagnosa CF (`hasil_diagnosis_cf`) | 6 kasus |
| Kasus yang bisa dipasangkan (user & set gejala sama) | 4 kasus |
| Kasus pasangan yang **hasil penyakitnya sama (akurat)** | **4 kasus** |
| Kasus pasangan yang hasil penyakitnya berbeda | 0 kasus |

Perhitungan akurasi dari kasus yang bisa dipasangkan:

```
Akurasi = (Jumlah kasus akurat ÷ Jumlah kasus yang berpasangan) × 100%
        = (4 ÷ 4) × 100%
        = 100%
```

**Catatan penting:**
- Angka "akurat" di atas dihitung dari **kesepakatan antara metode CBR dan CF** (keduanya menyimpulkan penyakit yang sama untuk kasus dengan input gejala yang sama), karena sistem saat ini belum memiliki kolom *ground truth* (diagnosis sebenarnya dari pakar) — kolom `divalidasi_pakar` masih bernilai 0 untuk semua kasus, dan `status_retain` mayoritas `baru` (hanya 1 kasus berstatus `divalidasi`).
- Untuk akurasi yang sesungguhnya, setiap hasil diagnosa perlu divalidasi oleh pakar (diisi sesuai label penyakit sebenarnya), lalu dihitung dengan rumus yang sama.
