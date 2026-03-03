# 🚀 M. Farras Majid - Portfolio Website

[![License: MIT](https://img.shields.io/badge/License-MIT-red.svg)](https://opensource.org/licenses/MIT)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

> Website portfolio profesional untuk M. Farras Majid — Web Developer, Data Engineer & DevOps Engineer.

![Portfolio Preview](images/og-image.jpg)

---

## 📋 Daftar Isi

- [Tentang](#-tentang)
- [Fitur](#-fitur)
- [Tech Stack](#-tech-stack)
- [Struktur Proyek](#-struktur-proyek)
- [Cara Instalasi](#-cara-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Halaman](#-halaman)
- [SEO](#-seo)
- [Performa](#-performa)
- [Kustomisasi](#-kustomisasi)
- [Deployment](#-deployment)
- [Lisensi](#-lisensi)
- [Kontak](#-kontak)

---

## 🎯 Tentang

Website portfolio ini dibuat untuk menampilkan profil, keahlian, proyek, dan artikel blog dari **M. Farras Majid**. Dibangun dengan HTML, CSS, dan JavaScript murni (vanilla) tanpa framework tambahan, sehingga ringan, cepat, dan mudah di-deploy di mana saja.

### Highlights:
- **Desain Glassmorphism** dengan tema hitam-merah (70% hitam, 30% merah)
- **Fully Responsive** - tampil sempurna di semua ukuran layar
- **SEO Optimized** - meta tags, structured data, dan semantic HTML
- **Contact Form** terhubung langsung ke email via FormSubmit
- **Zero Dependencies** - tidak memerlukan build tools atau package manager

---

## ✨ Fitur

### 🏠 Homepage
- Hero section dengan typing animation
- Statistik pengalaman kerja
- Floating cards dengan glassmorphism effect
- Smooth scroll navigation

### 👤 About Me
- Profil lengkap dengan foto
- Info kontak dan status availability
- Experience badge
- Deskripsi profesional

### 🛠 Skills
- 3 kategori skill: Web Dev, Data Engineering, DevOps
- Animated skill bars
- Icon teknologi yang relevan
- Progress bar dengan animasi scroll-triggered

### 💼 Projects
- Grid layout dengan filter kategori (All / Web / Data / DevOps / Open Source)
- Project cards dengan hover overlay
- Link ke live demo dan GitHub repository
- Preview gambar proyek

### 📝 Blog
- Artikel listing dengan category filter
- Blog card dengan thumbnail, metadata, dan excerpt
- Read more navigation
- Kategori: Web Dev, Data Engineering, DevOps, Tutorial

### 📬 Contact
- Form lengkap: nama, email, telepon, subjek, budget, pesan
- **Terhubung ke email via [FormSubmit.co](https://formsubmit.co/)**
- Anti-spam honeypot
- Validasi form client-side
- Info kontak dan social media links

### 🔗 Navigasi & UX
- Fixed navbar dengan scroll effect
- Mobile hamburger menu
- Back to top button
- Tombol "Hire Me" yang prominent
- Loading animation
- Smooth page transitions

### ⚙️ Technical
- Halaman error kustom (403, 404) sesuai tema
- robots.txt untuk SEO
- Schema.org structured data (JSON-LD)
- Open Graph & Twitter Card meta tags
- Lazy loading images
- Prefers-reduced-motion support
- Keyboard navigation support

---

## 🔧 Tech Stack

| Teknologi | Kegunaan |
|-----------|----------|
| **HTML5** | Struktur & Semantic Markup |
| **CSS3** | Styling, Glassmorphism, Animations, Responsive |
| **JavaScript (ES6+)** | Interaktivitas, Animasi, Form Handling |
| **Google Fonts (Inter)** | Typography |
| **Font Awesome 6** | Iconography |
| **FormSubmit.co** | Form-to-Email Service |

---

## 📁 Struktur Proyek

```
mfarrasmajid/
├── css/
│   └── style.css           # Stylesheet utama (glassmorphism, responsive)
├── js/
│   └── main.js             # JavaScript utama (animasi, navigasi, form)
├── images/
│   ├── profile.jpg          # Foto profil hero section
│   ├── about.jpg            # Foto about section
│   ├── og-image.jpg         # Open Graph image untuk social media
│   ├── projects/
│   │   ├── project-1.jpg    # Screenshot proyek 1
│   │   ├── project-2.jpg    # Screenshot proyek 2
│   │   ├── ...              # Screenshot proyek lainnya
│   │   └── project-9.jpg    # Screenshot proyek 9
│   └── blog/
│       ├── blog-1.jpg       # Thumbnail blog 1
│       ├── blog-2.jpg       # Thumbnail blog 2
│       ├── ...              # Thumbnail blog lainnya
│       └── blog-9.jpg       # Thumbnail blog 9
├── index.html               # Homepage utama
├── blog.html                # Halaman daftar artikel blog
├── projects.html            # Halaman semua proyek + repository
├── contact.html             # Halaman contact form
├── 404.html                 # Halaman error 404
├── 403.html                 # Halaman error 403
├── robots.txt               # File robots untuk SEO
└── README.md                # Dokumentasi proyek (file ini)
```

---

## 🚀 Cara Instalasi

### Prasyarat
- Web server (Apache/Nginx) atau bisa dijalankan secara lokal
- Browser modern (Chrome, Firefox, Edge, Safari)

### Langkah-langkah

1. **Clone repository:**
   ```bash
   git clone https://github.com/mfarrasmajid/portfolio.git
   cd portfolio
   ```

2. **Menggunakan XAMPP (Local Development):**
   ```
   - Copy folder ke: xampp/htdocs/mfarrasmajid/
   - Jalankan Apache dari XAMPP Control Panel
   - Buka browser: http://localhost/mfarrasmajid/
   ```

3. **Menggunakan VS Code Live Server:**
   ```
   - Install extension "Live Server"
   - Klik kanan pada index.html > "Open with Live Server"
   ```

4. **Menggunakan Python Simple Server:**
   ```bash
   python -m http.server 8000
   # Buka http://localhost:8000
   ```

---

## ⚙️ Konfigurasi

### 1. Setup Contact Form (Email)

Form di halaman contact menggunakan **FormSubmit.co** agar pesan langsung terkirim ke email.

1. Buka file `contact.html`
2. Cari baris berikut:
   ```html
   action="https://formsubmit.co/YOUR_EMAIL@gmail.com"
   ```
3. Ganti `YOUR_EMAIL@gmail.com` dengan email Anda
4. Submit form pertama kali untuk aktivasi (cek inbox untuk konfirmasi)
5. Setelah diaktivasi, semua submit selanjutnya langsung masuk email

### 2. Ganti Gambar

Ganti semua gambar placeholder di folder `images/`:

| File | Ukuran Rekomendasi | Keterangan |
|------|-------------------|------------|
| `profile.jpg` | 760x760px | Foto profil hero section |
| `about.jpg` | 800x900px | Foto about section |
| `og-image.jpg` | 1200x630px | Preview social media share |
| `projects/project-*.jpg` | 800x500px | Screenshot proyek |
| `blog/blog-*.jpg` | 800x500px | Thumbnail artikel |

### 3. Update Informasi Personal

- **Nama, Email, Bio**: Edit di `index.html` dan halaman lainnya
- **Social Media Links**: Cari dan ganti semua URL `linkedin.com/in/mfarrasmajid`, `github.com/mfarrasmajid`, dll
- **URL Website**: Ganti `https://mfarrasmajid.id` di meta tags

### 4. Update Domain di Meta Tags

Cari & replace di semua file HTML:
```
https://mfarrasmajid.id → https://yourdomain.com
```

---

## 📄 Halaman

| Halaman | File | Deskripsi |
|---------|------|-----------|
| **Home** | `index.html` | Hero, About, Skills, Projects, Blog, CTA |
| **Blog** | `blog.html` | Daftar semua artikel dengan filter kategori |
| **Projects** | `projects.html` | Semua proyek dengan link GitHub repository |
| **Contact** | `contact.html` | Form kontak + info + social media |
| **404** | `404.html` | Custom error page - Page Not Found |
| **403** | `403.html` | Custom error page - Access Forbidden |

---

## 🔍 SEO

Website ini sudah dioptimasi untuk SEO:

- ✅ **Semantic HTML5** - Menggunakan tag `<section>`, `<article>`, `<nav>`, `<footer>`, `<header>`
- ✅ **Meta Tags** - Title, description, keywords, author, robots
- ✅ **Open Graph** - Facebook, LinkedIn sharing preview
- ✅ **Twitter Card** - Twitter sharing preview
- ✅ **Schema.org** - JSON-LD structured data (Person)
- ✅ **robots.txt** - Search engine crawling rules
- ✅ **Alt Text** - Semua gambar memiliki alt text deskriptif
- ✅ **Heading Hierarchy** - H1 > H2 > H3 yang terstruktur
- ✅ **Semantic URLs** - Clean URL structure
- ✅ **Mobile Friendly** - Fully responsive design
- ✅ **Fast Loading** - No framework overhead, minimal assets

### Rekomendasi Tambahan:
- Tambahkan `sitemap.xml` untuk indexing yang lebih baik
- Setup Google Search Console dan submit sitemap
- Tambahkan Google Analytics untuk tracking

---

## ⚡ Performa

- **No build process** - Langsung serve file statis
- **Minimal HTTP requests** - CSS & JS masing-masing 1 file
- **Lazy loading** - Gambar dimuat saat dibutuhkan
- **Font preconnect** - Google Fonts dioptimasi dengan preconnect
- **Efficient CSS** - Menggunakan CSS variables untuk konsistensi
- **SVG placeholder** - Auto-generated placeholder untuk gambar yang gagal load
- **Reduced motion** - Respects `prefers-reduced-motion` user preference

---

## 🎨 Kustomisasi

### Mengubah Warna Tema

Edit CSS variables di `css/style.css`:

```css
:root {
  --bg-primary: #0a0a0a;       /* Background utama */
  --bg-secondary: #111111;      /* Background secondary */
  --red-primary: #e63946;       /* Warna aksen utama */
  --red-secondary: #c1121f;     /* Warna aksen hover */
  --red-light: #ff6b6b;         /* Warna aksen light */
  --text-primary: #f1f1f1;      /* Warna teks utama */
  --text-secondary: #b0b0b0;    /* Warna teks secondary */
}
```

### Mengubah Font

Ganti Google Fonts URL di `<head>` semua file HTML dan update `font-family` di CSS.

### Menambah Proyek Baru

Copy template project card di `projects.html` dan sesuaikan:

```html
<div class="project-card glass fade-in" data-category="web">
  <div class="project-thumb">
    <img src="images/projects/your-project.jpg" alt="Project Name">
    <div class="project-overlay">
      <a href="DEMO_URL"><i class="fas fa-external-link-alt"></i></a>
      <a href="GITHUB_URL"><i class="fab fa-github"></i></a>
    </div>
  </div>
  <div class="project-info">
    <div class="project-tags">
      <span>Tech1</span>
      <span>Tech2</span>
    </div>
    <h3>Project Title</h3>
    <p>Project description...</p>
  </div>
</div>
```

---

## 🌐 Deployment

### GitHub Pages
```bash
git init
git add .
git commit -m "Initial portfolio"
git remote add origin https://github.com/username/portfolio.git
git push -u origin main
# Enable GitHub Pages di Settings > Pages > Source: main branch
```

### Netlify
1. Push ke GitHub
2. Login ke [netlify.com](https://netlify.com)
3. New site from Git > pilih repository
4. Deploy otomatis!

### Vercel
```bash
npm i -g vercel
vercel
```

### VPS / Shared Hosting
Upload semua file ke `public_html/` via FTP/SFTP.

### Apache `.htaccess` (Error Pages)
```apache
ErrorDocument 404 /404.html
ErrorDocument 403 /403.html
```

---

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

Anda bebas menggunakan, memodifikasi, dan mendistribusikan kode ini untuk keperluan pribadi maupun komersial.

---

## 📬 Kontak

**M. Farras Majid**

- 🌐 Website: [mfarrasmajid.id](https://mfarrasmajid.id)
- 📧 Email: muhammad.farras@mitratel.co.id
- 💼 LinkedIn: [linkedin.com/in/m-farras-majid](https://linkedin.com/in/m-farras-majid)
- 🐙 GitHub: [github.com/mfarrasmajid](https://github.com/mfarrasmajid)

---

<p align="center">
  Made with ❤️ by M. Farras Majid
</p>
