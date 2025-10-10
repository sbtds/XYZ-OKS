// src/assets/js/icons.js
// 使用しているアイコンのみをインポート
import { library } from "@fortawesome/fontawesome-svg-core";
import { faEnvelope as farEnvelope } from "@fortawesome/free-regular-svg-icons";
import {
  faArrowRight,
  faArrowUp,
  faEnvelope,
  faAngleDown,
  faAngleRight,
  faAngleLeft,
  faSearch,
  faXmark,
  faLocationDot,
  faChevronDown,
  faChevronLeft,
  faChevronRight,
  faBriefcase,
  faPlus,
  faMinus,
} from "@fortawesome/free-solid-svg-icons";

// 必要なアイコンのみをライブラリに追加
library.add(
  // Regular icons
  farEnvelope,
  // Solid icons
  faArrowRight,
  faArrowUp,
  faEnvelope,
  faAngleDown,

  faAngleRight,
  faAngleLeft,
  faSearch,
  faXmark,
  faLocationDot,
  faChevronDown,
  faChevronLeft,
  faChevronRight,
  faBriefcase,
  faPlus,
  faMinus,
);
