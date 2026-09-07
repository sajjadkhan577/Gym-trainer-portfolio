---
name: Kinetic Noir
colors:
  surface: '#12131a'
  surface-dim: '#12131a'
  surface-bright: '#383940'
  surface-container-lowest: '#0c0e14'
  surface-container-low: '#1a1b22'
  surface-container: '#1e1f26'
  surface-container-high: '#282a31'
  surface-container-highest: '#33343c'
  on-surface: '#e2e1eb'
  on-surface-variant: '#bccbb9'
  inverse-surface: '#e2e1eb'
  inverse-on-surface: '#2f3037'
  outline: '#869585'
  outline-variant: '#3d4a3d'
  surface-tint: '#4ae176'
  primary: '#4be277'
  on-primary: '#003915'
  primary-container: '#22c55e'
  on-primary-container: '#004b1e'
  inverse-primary: '#006e2f'
  secondary: '#c8c6c5'
  on-secondary: '#313030'
  secondary-container: '#4a4949'
  on-secondary-container: '#bab8b7'
  tertiary: '#c7c7c7'
  on-tertiary: '#303030'
  tertiary-container: '#acacac'
  on-tertiary-container: '#404040'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#6bff8f'
  primary-fixed-dim: '#4ae176'
  on-primary-fixed: '#002109'
  on-primary-fixed-variant: '#005321'
  secondary-fixed: '#e5e2e1'
  secondary-fixed-dim: '#c8c6c5'
  on-secondary-fixed: '#1c1b1b'
  on-secondary-fixed-variant: '#474646'
  tertiary-fixed: '#e2e2e2'
  tertiary-fixed-dim: '#c6c6c6'
  on-tertiary-fixed: '#1b1b1b'
  on-tertiary-fixed-variant: '#474747'
  background: '#12131a'
  on-background: '#e2e1eb'
  surface-variant: '#33343c'
typography:
  display-xl:
    fontFamily: Montserrat
    fontSize: 72px
    fontWeight: '900'
    lineHeight: '1.1'
    letterSpacing: -0.04em
  headline-lg:
    fontFamily: Montserrat
    fontSize: 48px
    fontWeight: '800'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Montserrat
    fontSize: 32px
    fontWeight: '800'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Montserrat
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-caps:
    fontFamily: Montserrat
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1'
    letterSpacing: 0.1em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 20px
  margin-desktop: 64px
---

## Brand & Style

The design system is centered on an elite, high-performance fitness aesthetic that blends the raw energy of underground training with the refined polish of luxury technology. The visual direction utilizes a **Dark Mode Glassmorphism** approach, creating a sense of sophisticated depth through layered transparency and luminous accents.

The target audience consists of high-end athletes and fitness professionals who value precision, exclusivity, and intensity. The UI should evoke a sense of focused power—heavy, dark surfaces are punctuated by electric neon strikes to guide the eye toward progress and action. Every interaction must feel frictionless yet intentional, mimicking the flow of a well-executed athletic movement.

## Colors

This design system utilizes a high-contrast palette optimized for low-light environments. 

- **Primary Neon (#22C55E):** Reserved exclusively for critical calls to action, active progress indicators, and successful state messages. It represents "Go" and high energy.
- **Surface Foundations:** The base layer is pure black (#000000) to ensure deep blacks on OLED displays. Elevating elements are rendered in Charcoal (#121212) with varying levels of opacity.
- **Accent Glass:** Borders and dividers use a semi-transparent white (10-15% opacity) to catch light without breaking the dark aesthetic.

## Typography

Typography follows a "Large & Loud" hierarchy. **Montserrat** is used for all headings to provide a geometric, assertive presence. Weight is your primary tool for hierarchy; use ExtraBold and Black weights for impact.

**Inter** provides a utilitarian contrast for body copy and data. It ensures that complex fitness metrics remain legible even at small sizes. All labels should be set in uppercase Montserrat with increased letter spacing to denote secondary information or category tags.

## Layout & Spacing

The system employs a **Fluid Grid** model with generous margins to allow high-resolution fitness photography to breathe. 

- **Desktop:** 12-column grid with 64px side margins. Large components (like workout cards) should span 3 or 4 columns.
- **Mobile:** 4-column grid with 20px side margins. 
- **Vertical Rhythm:** Elements follow an 8px base unit. Section spacing should be aggressive (e.g., 120px between major blocks) to maintain the premium, editorial feel.

## Elevation & Depth

Depth is achieved through **Glassmorphism** rather than traditional shadows. Surfaces are not solid; they are windows.

- **The Glass Stack:** Background Blur (30px) + Translucent Charcoal (70% opacity) + 1px Inner Stroke (White, 10% opacity).
- **Z-Axis:** Higher elevation elements receive a slightly lighter background fill and a more pronounced border highlight.
- **Hover States:** Elements should feel "magnetic." On hover, increase the border opacity and apply a subtle glow effect using the primary neon green.

## Shapes

The shape language is predominantly "Rounded-XL." Large containers, cards, and buttons use a 1.5rem (24px) corner radius to soften the intensity of the dark color palette and provide a modern, tech-forward feel. Smaller elements like input fields and tags use a 0.5rem (8px) radius to maintain structural integrity.

## Components

### Buttons
- **Primary:** Solid Neon Green (#22C55E) with Black text. No border. On hover, apply a 20px box-shadow glow of the same color.
- **Ghost:** Transparent background with a 2px Neon Green border. 
- **Glass:** Semi-transparent charcoal with a subtle white border, used for secondary navigation.

### Sticky Navigation
The header must be a fixed glassmorphism bar. It uses a `backdrop-filter: blur(20px)` and a thin bottom border (#FFFFFF 10% opacity). The logo should be high-contrast white.

### Cards
Cards are the primary container for workout programs and trainer profiles. They must feature a dark gradient overlay on the bottom third to ensure white typography is legible over fitness photography.

### Progress Bars
Trackers should use a "Glowing Pulse" effect. The background track is dark charcoal, while the active fill is Neon Green with a soft outer glow.

### Form Fields
Inputs use a "bottom-line only" or "soft-recessed" glass style. Labels remain small and uppercase. The focus state must trigger a Neon Green border transition.