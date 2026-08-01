import '@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css';
import '@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js';
import '../css/app.css';
function initCalendar() {
    if (typeof window.jalaliDatepicker === "undefined") return;

    window.jalaliDatepicker.startWatch({
        selector: ".datepicker",
        persianDigits: true,
        format: "YYYY/MM/DD",
    });
}

document.addEventListener("DOMContentLoaded", initCalendar);

// وقتی Livewire state تغییر کرد
document.addEventListener("livewire:navigated", () => {
    setTimeout(initCalendar, 50);
});

// باز کردن تقویم بعد از انتخاب کارمند
document.addEventListener("open-calendar", () => {
    const input = document.querySelector(".datepicker");

    if (!input) return;

    input.focus();
    input.click();
});
