const colors = require("tailwindcss/colors");
const custom_colors = {
  ...colors,
  ...{
    primary: "#18ad9b",
    "primary-light": "#5fb7ad",
    "primary-white": "#e5f4f2",
    "primary-dark": "#047c6e",
    "primary-black": "#044586",
    "primary-gray": "#7a9fcf",
    "primary-gray-light": "#a3bde0",
    secondary: "#f11847",
    "secondary-light": "#ffc11d",
    "secondary-dark": "#e61515",
    contrast: "#000000",
    back: "#f4f3ee",
    "back-light": "#f5f9ff",
    "back-dark": "#ebeae7",
    gray: "#e5e7eb",
    "gray-light": "#f6f6f6",
  },
};

module.exports = {
  content: ["./src/*.html", "./src/**/*.html", "./src/**/*.js", "./src/**/*.scss"],
  theme: {
    screens: {
      sm: "480px",
      md: "840px",
      // lg: '1020px',
      lg: "1080px",
      // lg: '1120px',
      // xl: '1410px',
      xl: "1280px",
      // xl: '1236px',
      "2xl": "1440px",

      // 高さベースのブレークポイント
      laptop: { raw: "(max-height: 800px)" }, // ノートパソコン画面（低め）
      desktop: { raw: "(min-height: 801px) and (max-height: 1080px)" }, // 標準的なデスクトップ
      large: { raw: "(min-height: 1081px)" }, // 大きい画面
    },

    colors: custom_colors,

    fontSize: {
      "4xs": "0.56rem",
      "3xs": "0.66rem",
      "2xs": "0.76rem",
      xs: "0.8rem",
      sm: "0.86rem",
      base: "1.0rem",
      lg: "1.15rem",
      xl: "1.24rem",
      "2xl": "1.35rem",
      "3xl": "1.65rem",
      "4xl": "2.1rem",
      "5xl": "2.5rem",
      "6xl": "3.0rem",
      "7xl": "3.4rem",
      "8xl": "4.0rem",
      "9xl": "4.5rem",
      "10xl": "5.0rem",
      "11xl": "5.6rem",
      en: "1.0em",
    },
    extend: {
      // Tailwindユーティリティ拡張
      // hover: を使用したクラスを追加
      textColor: {
        "hover-black": "#000000",
      },
      backgroundColor: {
        "hover-white": "#FFFFFF",
      },
      writingMode: {
        "vertical-rl": "vertical-rl",
        "vertical-lr": "vertical-lr",
        "horizontal-tb": "horizontal-tb",
      },
    },
  },
  variants: {
    extend: {
      // hover-child などのバリアントを有効化
      backgroundColor: ["hover", "hover-child"],
      textColor: ["hover", "hover-child"],
    },
  },
  plugins: [
    // hover-child プラグイン
    function ({ addVariant }) {
      addVariant("hover-child", "&:hover > *");
    },
    // Writing mode utilities
    function ({ addUtilities }) {
      const newUtilities = {
        ".writing-mode-vertical-rl": {
          "writing-mode": "vertical-rl",
        },
        ".writing-mode-vertical-lr": {
          "writing-mode": "vertical-lr",
        },
        ".writing-mode-horizontal-tb": {
          "writing-mode": "horizontal-tb",
        },
        ".text-orientation-mixed": {
          "text-orientation": "mixed",
        },
        ".text-orientation-upright": {
          "text-orientation": "upright",
        },
      };
      addUtilities(newUtilities);
    },
  ],
};
