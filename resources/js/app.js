import "./bootstrap";
import Alpine from "alpinejs";

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
    if (!dateObject.isValid()) {
        return;
    }
    let fullDateFormat = "D MMMM YYYY, HH:mm";
    const fullDateTime = dayjs(dateObject).local().format(fullDateFormat);
    el.textContent = fullDateTime;
});

Alpine.directive("dateformat", (el) => {
    const dateString = `${el.textContent}`.trim();
    const dateObject = dayjs.utc(dateString);
    if (!dateObject.isValid()) {
        return;
    }
    let fullDateFormat = "D MMMM YYYY";
    const fullDateTime = dayjs(dateObject).local().format(fullDateFormat);
    el.textContent = fullDateTime;
});

Alpine.directive("datetimehuman", (el) => {
    const dateString = `${el.textContent}`.trim();
    const dateObject = dayjs.utc(dateString);
    if (!dateObject.isValid()) {
        return;
    }
    let fullDateFormat = "D MMMM YYYY, HH:mm";
    if (dayjs().isSame(dateObject, "year")) {
        fullDateFormat = "ddd D MMMM, HH:mm";
    }
    const fullDateTime = dayjs(dateObject).local().format(fullDateFormat);
    el.textContent = fullDateTime;
});

window.Alpine = Alpine;

Alpine.start();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
