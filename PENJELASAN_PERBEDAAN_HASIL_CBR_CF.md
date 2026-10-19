# Penjelasan Perbedaan Hasil Diagnosa Metode CBR dan CF

## Ringkasan

Meskipun kedua metode menerima **gejala input yang sama** dan sama-sama menghasilkan penyakit **Bulai**, nilai tingkat keyakinannya berbeda:

| Metode | Hasil Penyakit | Nilai |
|--------|---------------|-------|
| **CBR** | Bulai | Similarity ≈ **67.35%** |
| **CF** | Bulai | Certainty Factor ≈ **98.00%** |

Perbedaan ini **bukan kesalahan sistem**. Kedua metode memang dirancang untuk mengukur "kecocokan" dengan cara yang berbeda, sehingga menghasilkan angka yang berbeda walau mengarah ke penyakit yang sama.

---

## 1. Cara Hitung Metode CBR (Case-Based Reasoning)

CBR bekerja dengan mencari **kasus/pengetahuan terdahulu yang paling mirip** dengan input pengguna. Rumus yang digunakan pada sistem ini:

```
Similarity = (Total bobot gejala input yang cocok dengan basis gejala penyakit)
             ÷ (Total bobot seluruh gejala pada basis gejala penyakit)
```

**Karakteristik:**
- Mengukur *proporsi gejala yang cocok* dari total gejala yang tersimpan di basis pengetahuan penyakit tersebut.
- Jika penyakit Bulai memiliki banyak gejala di databasenya tetapi pengguna hanya memilih sebagian, maka similarity-nya akan **terbagi** oleh semua gejala tersebut.
- Contoh: dari 8 gejala basis Bulai (total bobot 100%), 4 gejala yang dialami pengguna hanya mencakup bobot 67.35%, maka hasil CBR = **67.35%**.
- Nilai CBR bisa rendah meskipun penyakitnya benar, karena CBR juga "menghukum" jika informasi yang diberikan pengguna belum lengkap.

## 2. Cara Hitung Metode CF (Certainty Factor)

CF mengukur **tingkat keyakinan** pakar terhadap setiap gejala, dikombinasikan dengan keyakinan pengguna. Rumus gabungan (kombinasi certainty factor):

```
CF(H,E) = CF pakar gejala × CF pengguna

Gabungan beberapa gejala:
CF1+CF2 = CF1 + CF2 × (1 − CF1)
```

**Karakteristik:**
- Setiap gejala dihitung sendiri (`CF pakar × CF user`), lalu digabung satu per satu.
- Setiap tambahan gejala yang cocok akan **menaikkan** CF gabungan dan mendekati 100%.
- Dengan beberapa gejala yang cocok sekaligus (dengan CF pakar cukup tinggi), hasil mudah mencapai **> 90%**, maka CF ≈ **98.00%** wajar terjadi.
- Sistem juga menerapkan *threshold* minimal 0.5 (50%) — penyakit dengan CF di bawah itu dianggap tidak valid.

## 3. Mengapa Sama Penyakitnya Tapi Angka Berbeda?

| Aspek | CBR | CF |
|-------|-----|-----|
| Yang diukur | Kemiripan gejala dengan kasus terdahulu | Keyakinan (belief) terhadap hipotesis penyakit |
| Rumus | Rasio pembobotan gejala cocok / total bobot | Kombinasi bertingkat `CF1 + CF2(1−CF1)` |
| Arah nilai | Semakin lengkap gejala input → makin tinggi | Semakin banyak gejala cocok → makin mendekati 100% |
| Angka tinggi berarti | Input pengguna mencakup hampir semua gejala basis | Gejala-gejala yang ada sangat meyakinkan |

Kesimpulannya:
- **67.35% (CBR)** berarti "gejala yang kamu masukkan mencakup sekitar 67% bobot gejala khas Bulai."
- **98.00% (CF)** berarti "dari gejala yang cocok, tingkat keyakinan pakar sangat tinggi sehingga CF gabungan hampir pasti Bulai."

Keduanya konsisten mengarah ke penyakit yang sama, hanya **sudut pandang pengukurannya berbeda**. Inilah justru keuntungan sistem dengan dua metode — hasil diagnosa bisa saling memvalidasi.

---

## 4. Kasus Kedua: CBR 100% dan CF 99.70%

Pada pengujian kedua, hasilnya menjadi:

| Metode | Hasil Penyakit | Nilai |
|--------|---------------|-------|
| **CBR** | Bulai | Similarity ≈ **100.00%** |
| **CF** | Bulai | Certainty Factor ≈ **99.70%** |

**Penjelasan:**

- **CBR mencapai 100%** karena gejala yang dipilih pengguna sudah mencakup **seluruh gejala basis** penyakit Bulai, sehingga total bobot gejala cocok = total bobot seluruh gejala basis. Rasio pembobotannya menjadi 1 (100%).

- **CF tidak tepat 100% (99.70%)** karena:
  1. Nilai CF tiap gejala adalah `CF pakar × CF pengguna`. Jika CF pakar (misal 0.8 atau 0.9) dan CF pengguna default (1.0), hasil tiap gejala maksimal hanya sebesar CF pakar tersebut, tidak pernah tepat 1.
  2. Rumus kombinasi `CF1 + CF2×(1−CF1)` sifatnya **konvergen mendekati 1 tapi tidak pernah tepat 1** selama nilai komponennya < 1. Semakin banyak gejala, nilainya semakin dekat ke 100% (misal 0.997 = 99.70%), tapi tetap di bawahnya.

**Kesimpulan:** Semakin lengkap gejala yang dimasukkan, CBR bisa tepat 100% (karena berbasis rasio bobot), sedangkan CF akan sangat mendekati 100% (karena berbasis keyakinan gabungan yang sifatnya asimtotik). Keduanya tetap sama-sama menunjukkan tingkat keyakinan yang **sangat tinggi**.

---

*Dokumen ini hanya dokumentasi penjelasan, tidak ada perubahan kode.*
