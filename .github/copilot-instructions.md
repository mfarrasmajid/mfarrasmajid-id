# Portfolio Website (mfarrasmajid.id) - AI Agent Guidelines

## Project Overview
This is a personal portfolio website for Farras Majid, showcasing career progression, projects, and skills through an interactive timeline interface. The site features two main views: a card-swipe interface (`index.html`) and a chronological timeline (`profile.html`).

## Architecture & Structure

### Core Components
- **`index.html`**: Interactive card deck with swipe mechanics (5 random cards at a time)
- **`profile.html`**: Full chronological timeline with year-based sections
- **Individual project pages**: `tracking-spmk.html`, `transport-management.html`, `womtools.html`, `40x.html`
- **`bak/` directory**: Contains detailed project showcase pages (backup/archive)

### Technology Stack
- **Frontend**: Vanilla HTML5, CSS3, JavaScript (no frameworks)
- **Backend**: PHP for contact forms with PHPMailer integration
- **UI Libraries**: Bootstrap-based (`bootsnav.css`), Revolution Slider, jQuery
- **Server Environment**: Apache/XAMPP (indicated by file structure)

## Data Architecture Pattern

### Timeline Data Structure
All project/career data is **embedded directly in JavaScript arrays** within HTML files:
```javascript
const projects = [
  {
    imgSrc: "images/_shadalkane/project/image.png",
    altText: "Project Name",
    date: "Month Year", 
    title: "Project Title",
    description: "Full description with tech stack",
    classs: "cover cover-left" // Image positioning
  }
  // ... more entries
];
```

### Critical Patterns
- **No external APIs or databases** - all content is hardcoded in JS arrays
- **Image organization**: `images/_shadalkane/[project-name]/` for project screenshots
- **Consistent tech stack mentions**: "Laravel, HTML, CSS, Javascript, dan MySQL"
- **Indonesian language** throughout descriptions and UI text

## File Organization Conventions

### Image Asset Structure
- **Projects**: `images/_shadalkane/[project-name]/[project-name]-[number].png`
- **Icons/Skills**: `images/icons/[technology].png|svg|webp`
- **Career milestones**: `images/career/career[number].jpg`
- **Certifications**: `images/certification/[cert-name].jpeg`

### CSS & JS Dependencies
- **Core styles**: `css/style.css` (3870+ lines, comprehensive theme)
- **Navigation**: `css/bootsnav.css` + `js/bootsnav.js`
- **Main interactions**: `js/main.js` (1899+ lines, handles all animations/interactions)
- **Revolution Slider**: `revolution/` directory (complete slider framework)

## Development Workflows

### Adding New Projects/Timeline Entries
1. **Add to data arrays** in both `index.html` and `profile.html` JavaScript sections
2. **Follow date-based ordering** (newest first in arrays)
3. **Create image assets** in appropriate `images/_shadalkane/[project]/` directory
4. **Maintain consistent description format** with tech stack details

### Email Contact Forms
- **PHP backend**: `email-templates/contact-form.php`
- **SMTP configuration** available but disabled by default (`$enable_smtp = 'no'`)
- **PHPMailer integration** included but requires SMTP credentials

### Local Development
- **XAMPP environment** expected (Apache + PHP + MySQL)
- **No build process** - direct file editing and refresh
- **No package manager** - all dependencies included in repository

## Project-Specific Conventions

### Image Classification System
- `cover cover-top|bottom|left|right|center` for project screenshots
- `icon` class for technology/skill icons
- Special responsive handling for mobile (`@media (max-width: 768px)`)

### Bilingual Considerations
- **Primary language**: Indonesian (Bahasa Indonesia)
- **File paths/code**: English
- **UI text and descriptions**: Indonesian
- **Comments**: Mixed English/Indonesian

### Interactive Features
- **Card swipe mechanics** with pointer events (touch/mouse)
- **Progress tracking** showing cards viewed vs total
- **Year-based timeline navigation** with sticky year labels
- **Responsive design** with mobile-first considerations

## Integration Points

### External Dependencies
- **Google Fonts**: Roboto and Montserrat font families
- **Font Awesome**: Icon library via `css/font-icons.min.css`
- **jQuery**: Version included in `js/jquery.min.js`
- **Revolution Slider**: Complete framework in `revolution/` directory

### No External APIs
- **Self-contained**: No external API calls or database connections
- **Static hosting compatible**: Can run on any web server with PHP support
- **Offline-friendly**: All assets served locally except Google Fonts

When modifying this codebase, always maintain the chronological ordering, update both index and profile pages simultaneously, and follow the established Indonesian language patterns for user-facing content.