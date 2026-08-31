import '@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css';
import '@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js';
import '../css/app.css';
import * as echarts from 'echarts';

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

window.renderReservationChart = function (data) {

    const element = document.getElementById('reservation-chart');

    if (!element) {
        return;
    }

    const chart = echarts.init(element);

    chart.setOption({

        tooltip: {
            trigger: 'axis'
        },

        xAxis: {
            type: 'category',
            data: data.labels
        },

        yAxis: {
            type: 'value'
        },

        series: [
            {
                name: 'نوبت',
                type: 'line',
                smooth: true,
                data: data.values,

                areaStyle: {}
            }
        ]

    });

    window.addEventListener('resize', () => {
        chart.resize();
    });
};
