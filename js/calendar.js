const calendarEl = document.getElementById('calendar');
const monthYearEl =  document.getElementById('monthYear');
const modalEl = document.getElementById('eventModal');
let currentDate = new Date();


function renderCalendar(date = new Date()) {
    calendarEl.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();
    const today = new Date();


    const totalDays = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    //Display month and year
    monthYearEl.textContent = date.toLocaleDateString('es-MX', {       month: 'long',
            year: 'numeric'
    });

    const weekDays = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    weekDays.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.className = 'day-name';
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl);
    });


    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarEl.appendChild(document.createElement('div'));
    }

    // Loop through days
    for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        const cell = document.createElement('div');
        cell.className = 'day';

        if(
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ) {
            cell.classList.add('today');
        }


        const dateEl = document.createElement('div');
        dateEl.className = 'date-number';
        dateEl.textContent = day;
        cell.appendChild(dateEl);

        const eventToday = events.filter(e => e.date === dateStr);
        const eventBox = document.createElement('div');
        eventBox.className = 'events';


        //Render events
        eventToday.forEach(event => {
            const ev = document.createElement('div');
            ev.className = 'event';

            const courseEl = document.createElement('div');
            courseEl.className = 'course';
            courseEl.textContent = event.title.split(' - ')[0];

            const instructorEl = document.createElement('div');
            instructorEl.className = 'instructor';
            instructorEl.textContent = "Instructor: " + event.title.split(' - ')[1];

            const timeEl = document.createElement('div');
            timeEl.className = 'time';
            timeEl.textContent = "Reloj" + event.start_time + " - " + event.end_time;

            ev.appendChild(courseEl);
            ev.appendChild(instructorEl);
            ev.appendChild(timeEl);
            eventBox.appendChild(ev);
    });

    // Overlay buttons

    const overlay = document.createElement('div');
    overlay.className = 'day-overlay';

    const addBtn = document.createElement('button');

    addBtn.className = 'overlay-btn';

    addBtn.textContent = '+ Add';

    addBtn.onclick = e => {
        e.stopPropagation();
        openModalForAdd(dateStr);
    };

    overlay.appendChild(addBtn);

    if (eventToday.length > 0) {
        const editBtn = document.createElement('button');
        editBtn.className = 'overlay-btn';
        editBtn.onclick = e => {
            e.stopPropagation();
            openModalForEdit(eventToday);
        };

        overlay.appendChild(editBtn);
    }

    cell.appendChild(overlay);
    cell.appendChild(eventBox);
    calendarEl.appendChild(cell);

    }
}


//Add Event Modal
function openModalForAdd(dateStr) {
    document.getElementById('fromAction').value = 'add';
    document.getElementById('eventId').value = '';
    document.getElementById('courseName').value = '';
    document.getElementById('instructorName').value = '';
    document.getElementById('startDate').value = dateStr;
    document.getElementById('endDate').value = dateStr;
    document.getElementById('startTime').value = '09:00';
    document.getElementById('endTime').value = '10:00';


    const selector = document.getElementById('eventSelector');
    const wrapper = document.getElementById('eventSelectorWrapper');


    if (selector && wrapper) {
        selector.innerHTML = '';
        wrapper.style.display = 'none';
    }

    modalEl.style.display = "flex";
}


//Edit Event Modal
function openModalForEdit(eventsOnDate) {
    document.getElementById('fromAction').value = 'edit';
    modalEl.style.display = "flex";

    const selector = document.getElementById('eventSelector');
    const wrapper = document.getElementById('eventSelectorWrapper');
    selector.innerHTML = '<option disabled selected>Selecciona evento...</option> ';

    eventsOnDate.forEach(e => {
        const option = document.createElement('option');
        option.value = JSON.stringify(e);
        option.textContent = `${e.title} - (${e.start_time} a ${e.end_time})`;
        selector.appendChild(option);
    });

    if(eventsOnDate.length > 1) {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }

    handleEventSelection(JSON.stringify(eventsOnDate[0]));
}

// Populate from selected event
function handleEventSelection(eventJSON) {
    const event = JSON.parse(eventJSON);

    document.getElementById('eventId').value = event.id;
    document.getElementById('deleteEventId').value = event.id;

    const [course, instructor] = event.title.split (' - ').map(e => e.trim());
    document.getElementById('courseName').value = course || '';
    document.getElementById('instructorName').value = instructor || '';
    document.getElementById('startDate').value = event.start || '';
    document.getElementById('endDate').value = event.end || '';
    document.getElementById('startTime').value = event.start_time || '';
    document.getElementById('endTime').value = event.end_time || '';

}


function closeModal() {
    modalEl.style.display = "none";
}

// Month Navigation
function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar(currentDate);
}

// Live Digital Clock
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('clock');
    clock.textContent = [
        now.getHours().toString().padStart(2, '0'),
        now.getMinutes().toString().padStart(2, '0'),
        now.getSeconds().toString().padStart(2, '0')
    ].join(':');
}


// Initialization
renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);