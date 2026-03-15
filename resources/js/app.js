import Cropper from "cropperjs";
import maplibregl from "maplibre-gl";
import ApexCharts from 'apexcharts';
import "./bootstrap";

window.Cropper = Cropper;
window.maplibregl = maplibregl;
window.ApexCharts = ApexCharts;

// ─── Livewire v3 ships with its own Alpine.js ────────────────────────────────
// DO NOT import Alpine separately or call Alpine.start() — it causes
// "Detected multiple instances of Alpine" and breaks Livewire pagination.
//
// Register custom alpine directives using Livewire's hook instead:

document.addEventListener("alpine:init", () => {
    const Alpine = window.Alpine;
    if (!Alpine) return;

    Alpine.directive("uppercase", (el) => {
        el.textContent = el.textContent.toUpperCase();
    });

    Alpine.directive("numberformat", (el) => {
        const number = parseInt(el.textContent);
        el.textContent = number.toLocaleString();
    });

    Alpine.directive("datetimeformat", (el) => {
        const dateString = `${el.textContent}`.trim();
        const dateObject = dayjs.utc(dateString);
        if (!dateObject.isValid()) return;
        el.textContent = dayjs(dateObject).local().format("D MMMM YYYY, HH:mm");
    });

    Alpine.directive("dateformat", (el) => {
        const dateString = `${el.textContent}`.trim();
        const dateObject = dayjs.utc(dateString);
        if (!dateObject.isValid()) return;
        el.textContent = dayjs(dateObject).local().format("D MMMM YYYY");
    });

    Alpine.directive("datetimehuman", (el) => {
        const dateString = `${el.textContent}`.trim();
        const dateObject = dayjs.utc(dateString);
        if (!dateObject.isValid()) return;
        let fmt = "D MMMM YYYY, HH:mm";
        if (dayjs().isSame(dateObject, "year")) fmt = "ddd D MMMM, HH:mm";
        el.textContent = dayjs(dateObject).local().format(fmt);
    });
});

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
