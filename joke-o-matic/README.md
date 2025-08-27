# Joke-o-Matic

A WordPress plugin that displays jokes in a styled card grid with punchline reveal animations. Works both as a **shortcode** (no dependencies) and as an **Elementor widget** (if Elementor is installed).

## Features
- Fetch jokes from Official Joke API (transient cached for 10 minutes)
- Grid of cards with setup and revealable punchline
- Flip/Fade/Slide reveal animations
- Secure output and i18n-ready strings
- **Dual compatibility**: Works standalone OR with Elementor

## Installation
1. Copy the `joke-o-matic` folder into your WordPress `wp-content/plugins/` directory.
2. Activate the plugin in WordPress Admin > Plugins.

## Usage

### Option 1: Shortcode (works anywhere)(no dependencies)

```
[joke-o-matic count="6" reveal="flip"]
```

- Works in any WordPress post/page/widget
- No Elementor required
- Same functionality, same styling

**Shortcode parameters:**
- `count`: Number of jokes (1-24, default: 6)
- `reveal`: Animation type - flip, fade, or slide (default: flip)

**Examples:**
```
[joke-o-matic]
[joke-o-matic count="3" reveal="fade"]
[joke-o-matic count="12" reveal="slide"]
```

### Option 2: Elementor Widget (requires Elementor)
1. In Elementor editor, search for "Joke-o-Matic" widget
2. Drag it onto your page
3. Configure settings in the widget panel

- Full visual editor with controls
- Live preview in Elementor
- Same output as shortcode

## Notes
- API reference: https://github.com/15Dkatz/official_joke_api
- If the API is unavailable, a fallback joke is displayed
- No Elementor required for shortcode usage
