# MFARRASMAJID.ID# MFARRASMAJID.ID



[![Security Status](https://img.shields.io/badge/Security-Production%20Ready-success)](./SECURITY.md)**mfarrasmajid.id** adalah website pribadi yang menampilkan portofolio, pengalaman, dan karya Farras Majid.  

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)Website ini dibangun untuk memberikan gambaran profesional sekaligus personal branding di dunia digital.



**mfarrasmajid.id** adalah website portofolio personal yang menampilkan pengalaman profesional, proyek, dan pencapaian Farras Majid melalui interface timeline interaktif.## 🌐 Fitur Utama

- **Halaman Portofolio** — Menampilkan proyek-proyek yang pernah dikerjakan.

🌐 **Live Demo**: [mfarrasmajid.id](https://mfarrasmajid.id)- **Timeline Pengalaman** — Visualisasi perjalanan karier dan pencapaian.

- **Kontak & Media Sosial** — Memudahkan pengunjung terhubung langsung.

---

## 🛠️ Teknologi yang Digunakan

## ✨ Fitur Utama- **HTML5**, **CSS3**, **JavaScript** untuk struktur dan interaksi.

- **Bootstrap / TailwindCSS** untuk styling responsif.

### 🎴 Interactive Card Deck (`index.html`)- **jQuery** untuk manipulasi DOM dan efek interaktif.

- Swipe-based card interface dengan 5 random cards- **Font Awesome / Icon Library** untuk ikon.

- Touch & mouse support untuk interaksi- Hosting di **VPS / Shared Hosting** dengan domain `mfarrasmajid.id`.

- Progress tracking untuk cards yang telah dilihat

- Smooth animations dan transitions## 📦 Instalasi & Pengembangan Lokal

1. Clone repository ini:

### 📅 Chronological Timeline (`profile.html`)   ```bash

- Timeline view dengan sections berdasarkan tahun   git clone https://github.com/username/mfarrasmajid.id.git

- Sticky year labels untuk navigasi mudah
- Responsive design untuk mobile dan desktop
- Scroll-based reveal animations

### 📂 Detailed Project Pages
- Individual showcase pages untuk proyek utama
- Gallery images dengan Revolution Slider
- Tech stack dan project details
- Links ke project pages: `tracking-spmk.html`, `transport-management.html`, `womtools.html`, dll

### 📧 Contact Forms
- PHP-based contact forms dengan PHPMailer
- CSRF protection & rate limiting
- Email validation & sanitization
- SMTP & simple email support

---

## 🛠️ Tech Stack

### Frontend
- **HTML5** - Semantic markup structure
- **CSS3** - Custom styling dengan advanced animations
- **Vanilla JavaScript** - Interactive card mechanics & timeline
- **jQuery 3.x** - DOM manipulation & effects
- **Bootstrap-based UI** - Responsive navigation (`bootsnav.css`)
- **Revolution Slider** - Image carousels & galleries
- **Google Fonts** - Roboto & Montserrat typography

### Backend
- **PHP 7.4+** - Contact form processing
- **PHPMailer** - Email delivery system
- **Apache** - Web server dengan mod_rewrite

### Development Environment
- **XAMPP** - Local development stack
- **Git** - Version control
- No build tools - Direct file editing workflow

---

## 📁 Project Structure

```
portofolio/
├── index.html              # Interactive card deck (main page)
├── profile.html            # Chronological timeline view
├── tracking-spmk.html      # Project showcase page
├── transport-management.html
├── womtools.html
├── 40x.html               # Error page
├── css/
│   ├── style.css          # Main stylesheet (3870+ lines)
│   ├── bootsnav.css       # Navigation components
│   ├── responsive.css     # Mobile optimizations
│   └── custom.css         # Additional customizations
├── js/
│   ├── main.js            # Core interactions (1899+ lines)
│   ├── bootsnav.js        # Navigation behavior
│   ├── jquery.min.js      # jQuery library
│   └── hamburger-menu.js  # Mobile menu
├── images/
│   ├── _shadalkane/       # Project screenshots by folder
│   │   ├── tracking-spmk/
│   │   ├── transport-management/
│   │   └── [project-name]/
│   ├── icons/             # Technology & skill icons
│   ├── career/            # Career milestone images
│   └── certification/     # Certificate images
├── email-templates/
│   ├── contact-form.php   # Main contact handler
│   ├── security-functions.php  # Security utilities
│   ├── config.php.example # SMTP configuration template
│   └── README.md          # Setup instructions
├── bak/                   # Detailed project showcase pages
├── revolution/            # Revolution Slider framework
└── fonts/                 # Local font files
```

---

## 🚀 Quick Start

### Prerequisites
- **XAMPP** (Apache + PHP 7.4+)
- **Git** for cloning
- Code editor (VS Code recommended)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/mfarrasmajid/mfarrasmajid-id.git
   cd mfarrasmajid-id
   ```

2. **Move to XAMPP directory**
   ```bash
   # Windows
   move mfarrasmajid-id C:\xampp\htdocs\portofolio
   
   # Linux/Mac
   mv mfarrasmajid-id /opt/lampp/htdocs/portofolio
   ```

3. **Start Apache**
   - Open XAMPP Control Panel
   - Start Apache service

4. **Access the website**
   ```
   http://localhost/portofolio/
   ```

### Email Setup (Optional)

If you want to enable contact forms:

1. **Configure SMTP credentials**
   ```bash
   cd email-templates
   cp config.php.example config.php
   ```

2. **Edit `config.php`** with your SMTP details:
   ```php
   define('SMTP_HOST', 'smtp.example.com');
   define('SMTP_USERNAME', 'your-username');
   define('SMTP_PASSWORD', 'your-password');
   define('SMTP_PORT', 587);
   define('RECEIVER_EMAIL', 'your-email@example.com');
   ```

3. **Test the contact form** at `/contact-form-example.php`

📖 **Detailed setup**: See [`email-templates/README.md`](email-templates/README.md)

---

## 📝 Adding New Content

### Adding a Project/Timeline Entry

Content is stored in **JavaScript arrays** within HTML files. Update both `index.html` and `profile.html`:

**Example:**
```javascript
// In index.html and profile.html
const projects = [
  {
    imgSrc: "images/_shadalkane/your-project/your-project-1.png",
    altText: "Your Project",
    date: "Bulan Tahun",
    title: "Your Project Title",
    description: "Membuat aplikasi full stack dengan menggunakan stack Laravel, HTML, CSS, Javascript, dan MySQL.",
    classs: "cover cover-left" // or cover-top, cover-bottom, cover-right, cover-center
  },
  // ... existing entries
];
```

### Adding Project Images

1. Create project directory:
   ```bash
   mkdir images/_shadalkane/your-project
   ```

2. Add images with naming convention:
   ```
   your-project-1.png
   your-project-2.png
   your-project-3.png
   ```

3. Image positioning classes:
   - `cover cover-top` - Top alignment
   - `cover cover-bottom` - Bottom alignment
   - `cover cover-left` - Left alignment
   - `cover cover-right` - Right alignment
   - `cover cover-center` - Center alignment
   - `icon` - For skill/technology icons

### Creating Detailed Project Pages

1. Copy template from `bak/` directory
2. Update project details, images, and tech stack
3. Add screenshots to image gallery section
4. Link from main timeline entry (optional)

---

## 🎨 Customization

### Styling
- **Main theme**: `css/style.css` (comprehensive theme system)
- **Colors**: Update CSS variables in `:root`
- **Fonts**: Google Fonts imported in `<style>` sections
- **Responsive**: `css/responsive.css` for mobile breakpoints

### Animations
- **Card interactions**: Edit `index.html` JavaScript section
- **Timeline reveals**: Modify `profile.html` observer code
- **WOW.js**: Used in main.js for scroll animations

### Navigation
- **Menu items**: Edit navigation in HTML header sections
- **Mobile menu**: Configured in `js/hamburger-menu.js`
- **Sticky behavior**: Controlled by `js/bootsnav.js`

---

## 🔒 Security

This project implements comprehensive security measures:

- ✅ **Input validation & sanitization** (XSS prevention)
- ✅ **CSRF token protection** for all forms
- ✅ **Rate limiting** (5 attempts/hour)
- ✅ **Email header injection prevention**
- ✅ **HTTPS enforcement** via .htaccess
- ✅ **Content Security Policy** headers
- ✅ **Directory access control**
- ✅ **Secure credential management**

**Security Score: 92/100** - Production Ready ✅

📖 **Full documentation**: See [`SECURITY.md`](SECURITY.md) & [`IMPLEMENTATION_SUMMARY.md`](IMPLEMENTATION_SUMMARY.md)

---

## 📱 Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

### Features
- Responsive design (mobile-first)
- Touch events support
- Pointer events API
- CSS Grid & Flexbox
- Modern JavaScript (ES6+)

---

## 🐛 Troubleshooting

### Forms not working?
1. Check CSRF token implementation in HTML forms
2. Verify `config.php` exists with valid credentials
3. Check `email-templates/error.log` for details
4. Ensure PHP sessions are enabled

### Images not loading?
1. Verify image paths in JavaScript arrays
2. Check file permissions (755 for directories, 644 for files)
3. Ensure images exist in `images/_shadalkane/[project]/`

### .htaccess errors?
1. Verify `mod_rewrite` is enabled in Apache
2. Check Apache error log for details
3. Ensure `AllowOverride All` in Apache config

### Rate limiting issues?
1. Delete `email-templates/rate_limit.json` to reset
2. Adjust limits in `config.php`
3. Check file permissions on rate_limit.json

---

## 📂 Key Files Reference

| File | Purpose | Lines |
|------|---------|-------|
| `index.html` | Interactive card deck interface | ~800 |
| `profile.html` | Chronological timeline view | ~1100 |
| `css/style.css` | Main stylesheet & theme | 3870 |
| `js/main.js` | Core interactions & animations | 1899 |
| `email-templates/contact-form.php` | Form handler | ~130 |
| `email-templates/security-functions.php` | Security utilities | ~280 |

---

## 🌍 Deployment

### Production Checklist

- [ ] Update domain references in files
- [ ] Configure SSL certificate
- [ ] Set up SMTP credentials in `config.php`
- [ ] Test all forms with CSRF tokens
- [ ] Verify .htaccess rules work on host
- [ ] Enable error logging, disable display_errors
- [ ] Test on multiple devices/browsers
- [ ] Verify image optimization
- [ ] Set up analytics (optional)
- [ ] Configure backup strategy

### Hosting Requirements
- PHP 7.4 or higher
- Apache with mod_rewrite
- SSL certificate (Let's Encrypt recommended)
- At least 100MB storage
- Support for .htaccess files

---

## 🤝 Contributing

This is a personal portfolio project, but suggestions are welcome!

1. Fork the repository
2. Create feature branch (`git checkout -b feature/improvement`)
3. Commit changes (`git commit -am 'Add improvement'`)
4. Push to branch (`git push origin feature/improvement`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 👤 Author

**Farras Majid**
- Website: [mfarrasmajid.id](https://mfarrasmajid.id)
- GitHub: [@mfarrasmajid](https://github.com/mfarrasmajid)

---

## 🙏 Acknowledgments

- Revolution Slider for carousel framework
- Bootstrap team for UI components
- PHPMailer for email functionality
- Font Awesome for icons
- Google Fonts for typography

---

## 📊 Project Stats

- **Total Files**: 100+
- **Lines of CSS**: 3,870+
- **Lines of JavaScript**: 1,899+
- **Project Entries**: 50+
- **Years Covered**: 2015-2025
- **Technologies Showcased**: 20+

---

## 🔗 Quick Links

- [Security Documentation](SECURITY.md)
- [Implementation Summary](IMPLEMENTATION_SUMMARY.md)
- [Email Setup Guide](email-templates/README.md)
- [AI Agent Guidelines](.github/copilot-instructions.md)

---

**Last Updated**: November 2025  
**Status**: Active Development ✨
