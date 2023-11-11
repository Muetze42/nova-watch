const defaultTheme = require('tailwindcss/defaultTheme.js')
const colors = require('tailwindcss/colors.js')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./resources/views/app/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        mono: ['Fira Code VF', ...defaultTheme.fontFamily.mono]
      },
      colors: {
        primary: colors.slate,
        accent: colors.emerald,
        achromatic: colors.gray
      }
    }
  },
  plugins: [require('@tailwindcss/forms'), require('tailwind-scrollbar')({ nocompatible: true })]
}
