import './bootstrap';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
import "@flaticon/flaticon-uicons/css/all/all.css";

window.Swiper = Swiper;
Fancybox.bind("[data-fancybox]", {
  // optional: setting behavior, transition, dsb
});