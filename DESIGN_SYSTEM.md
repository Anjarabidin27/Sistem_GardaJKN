# Garda JKN - Enterprise Design System
**Version**: 1.0.0
**Role**: Senior UI/UX Output
**Philosophy**: Modern, Trustworthy, Data-Centric

---

## 1. Color Palette (Token System)

Kami menggunakan sistem token HSL/Hex untuk fleksibilitas tema (Dark/Light).

### Primary (Brand Identity)
Didasarkan pada kepercayaan dan stabilitas institusi.
- **Brand Primary**: `#1E3A8A` (Deep Blue) - *Digunakan untuk Sidebar, Header Active, Primary Button.*
- **Brand Secondary**: `#3B82F6` (Bright Blue) - *Digunakan untuk highlights, links, focus rings.*
- **Brand Surface**: `#EFF6FF` (Ice Blue) - *Digunakan untuk background aktif pada sidebar.*

### Functional Colors (Signal)
- **Success**: `#16A34A` - *Status Aktif, Toast Berhasil.*
- **Warning**: `#FACC15` - *Status Pending, Alert.*
- **Danger**: `#DC2626` - *Delete, Error Text, Status Non-Aktif.*
- **Info**: `#0EA5E9` - *Help text, Tooltips.*

### Neutral & Backgrounds
- **Light BG**: `#F3F4F6` (Cool Gray 100)
- **Dark BG**: `#0F172A` (Slate 900)
- **Card Light**: `#FFFFFF`
- **Card Dark**: `#1E293B`
- **Text Primary**: `#111827` (Gray 900) / `#F8FAFC` (Slate 50) in Dark
- **Text Secondary**: `#6B7280` (Gray 500) / `#94A3B8` (Slate 400) in Dark

---

## 2. Typography System (Scale)

**Font Family**: `Inter` (UI/Data) & `Poppins` (Headings).

| Role | Size (rem/px) | Weight | Line Height | Tracking |
|------|---------------|--------|-------------|----------|
| **Display H1** | 2.0 / 32px | Bold (700) | 1.2 | -0.02em |
| **Page H2** | 1.5 / 24px | Semibold (600) | 1.3 | -0.01em |
| **Section H3** | 1.25 / 20px | Medium (500) | 1.4 | Normal |
| **Body Base** | 1.0 / 16px | Regular (400) | 1.5 | Normal |
| **Small/Label** | 0.875 / 14px | Medium (500) | 1.5 | 0.01em |
| **Tiny/Meta** | 0.75 / 12px | Regular (400) | 1.5 | 0.02em |

---

## 3. Spacing System (8pt Grid)

Konsistensi adalah kunci kerapian layout.
- `xs` (4px): Jarak icon dengan text.
- `sm` (8px): Padding internal button, gap antar button.
- `md` (16px): Padding card, gap antar kolom form.
- `lg` (24px): Padding container, gap antar section.
- `xl` (32px): Margin bottom section besar.
- `2xl` (48px): Layout margins.

---

## 4. Components Specification

### A. Buttons
- **Shape**: `border-radius: 8px` (Modern, soft but firm).
- **Height**: `40px` (Desktop), `48px` (Touch target).
- **Interactive**:
  - `Hover`: Brightness +5%, Logaritmic Scale.
  - `Active`: Scale 0.98 (Subtle push effect).
  - `Disabled`: Opacity 0.5, Cursor not-allowed.

### B. Cards (Data Containers)
- **Design**: White bg, Thin Border (`#E5E7EB`), Shadow-sm (`0 1px 2px 0 rgb(0 0 0 / 0.05)`).
- **Hover**: Shadow-md, Translate-y -2px (hanya untuk clickable cards).

### C. Forms (Inputs)
- **Style**: Outlined, Border `#D1D5DB`.
- **Focus**: Border `#3B82F6`, Ring 2px `#3B82F6` (Opacity 0.2).
- **Error**: Border `#DC2626`, Error text size 12px below input.

---

## 5. UX Behaviors & Micro-interactions

1.  **Loading States**:
    - Jangan gunakan "Loading..." text saja.
    - Gunakan **Skeleton Loaders** (kotak abu-abu berdenyut) yang meniru bentuk konten data.

2.  **Transitions**:
    - Global transition: `all 200ms cubic-bezier(0.4, 0, 0.2, 1)`.
    - Sidebar collapse: Smooth width transition.

3.  **Responsive Strategy**:
    - **Desktop**: Sidebar Fixed.
    - **Tablet**: Sidebar Collapsed (Icon only).
    - **Mobile**: Hamburger Menu -> Sidebar Drawer (Overlay).
    - **Tables**: Horizontal Scroll wrapper dengan sticky first column jika memungkinkan.

---

## 6. Accessibility (A11Y)
- Kontras rasio teks minimal 4.5:1.
- Fokus state harus terlihat jelas (outline biru).
- Semantic HTML (`<nav>`, `<main>`, `<label>`).
