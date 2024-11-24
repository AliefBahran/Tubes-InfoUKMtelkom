// date.js

// Menentukan bulan saat ini (November 2024)
const months = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const daysInMonth = [
    31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
];

const events = {}; // Untuk menyimpan acara berdasarkan tanggal

// Fungsi untuk generate kalender bulan tertentu
function generateMonthCalendar(monthIndex, year) {
    const calendarElement = document.getElementById("novemberCalendar");
    calendarElement.innerHTML = ''; // Clear calendar before rendering

    const monthDiv = document.createElement("div");
    monthDiv.classList.add("month");

    // Header Bulan
    const monthHeader = document.createElement("div");
    monthHeader.classList.add("month-header");
    monthHeader.textContent = `${months[monthIndex]} ${year}`;
    monthDiv.appendChild(monthHeader);

    // Body bulan (grid hari)
    const monthBody = document.createElement("div");
    monthBody.classList.add("month-body");

    // Menampilkan nama hari (Senin, Selasa, ... Sabtu)
    const weekDays = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    weekDays.forEach(day => {
        const dayElement = document.createElement("div");
        dayElement.textContent = day;
        monthBody.appendChild(dayElement);
    });

    // Menentukan hari pertama bulan ini (untuk penempatan tanggal)
    const firstDay = new Date(year, monthIndex, 1).getDay();

    // Menambahkan spasi kosong untuk hari pertama
    for (let i = 0; i < firstDay; i++) {
        const blankDiv = document.createElement("div");
        monthBody.appendChild(blankDiv);
    }

    // Menambahkan hari-hari dalam bulan
    for (let day = 1; day <= daysInMonth[monthIndex]; day++) {
        const dayDiv = document.createElement("div");
        dayDiv.classList.add("day");
        dayDiv.textContent = day;
        dayDiv.dataset.date = `${year}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // Menambahkan acara jika ada
        if (events[dayDiv.dataset.date]) {
            dayDiv.classList.add("has-event");
        }

        // Menambahkan event listener untuk menandai tanggal dan menambahkan keterangan
        dayDiv.addEventListener("click", function() {
            openEventModal(dayDiv.dataset.date);
        });

        monthBody.appendChild(dayDiv);
    }

    monthDiv.appendChild(monthBody);
    calendarElement.appendChild(monthDiv);
}

// Fungsi untuk generate kalender penuh (12 bulan)
function generateFullCalendar(year) {
    const fullCalendarElement = document.getElementById("fullCalendar");
    fullCalendarElement.innerHTML = ''; // Clear full calendar before rendering

    for (let monthIndex = 0; monthIndex < 12; monthIndex++) {
        const monthDiv = document.createElement("div");
        monthDiv.classList.add("month");

        // Header bulan
        const monthHeader = document.createElement("div");
        monthHeader.classList.add("month-header");
        monthHeader.textContent = `${months[monthIndex]} ${year}`;
        monthDiv.appendChild(monthHeader);

        // Body bulan
        const monthBody = document.createElement("div");
        monthBody.classList.add("month-body");

        // Menampilkan nama hari (Senin, Selasa, ... Sabtu)
        const weekDays = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        weekDays.forEach(day => {
            const dayElement = document.createElement("div");
            dayElement.textContent = day;
            monthBody.appendChild(dayElement);
        });

        // Menentukan hari pertama bulan ini
        const firstDay = new Date(year, monthIndex, 1).getDay();

        // Menambahkan spasi kosong untuk hari pertama
        for (let i = 0; i < firstDay; i++) {
            const blankDiv = document.createElement("div");
            monthBody.appendChild(blankDiv);
        }

        // Menambahkan hari-hari dalam bulan
        for (let day = 1; day <= daysInMonth[monthIndex]; day++) {
            const dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            dayDiv.textContent = day;
            dayDiv.dataset.date = `${year}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            // Menambahkan acara jika ada
            if (events[dayDiv.dataset.date]) {
                dayDiv.classList.add("has-event");
            }

            // Menambahkan event listener untuk menandai tanggal dan menambahkan keterangan
            dayDiv.addEventListener("click", function() {
                openEventModal(dayDiv.dataset.date);
            });

            monthBody.appendChild(dayDiv);
        }

        monthDiv.appendChild(monthBody);
        fullCalendarElement.appendChild(monthDiv);
    }
}

// Modal untuk menambahkan acara (termasuk jam dan tempat)
function openEventModal(date) {
    const description = prompt("Masukkan keterangan untuk tanggal " + date);
    if (description) {
        // Meminta waktu mulai acara dengan format 12 jam (AM/PM) atau 24 jam
        const startTime = prompt("Masukkan jam mulai acara (misal: 14:00 atau 2:00 PM)");

        // Meminta waktu selesai acara dengan format yang sama
        const endTime = prompt("Masukkan jam selesai acara (misal: 16:00 atau 4:00 PM)");

        // Meminta tempat acara
        const location = prompt("Masukkan tempat acara");

        // Simpan acara dengan tanggal, keterangan, jam mulai, jam selesai, dan tempat
        events[date] = {
            description: description,
            startTime: startTime,
            endTime: endTime,
            location: location
        };

        // Update daftar acara
        updateEventList();

        // Update kalender bulan November
        generateMonthCalendar(10, 2024);

        // Update kalender penuh
        generateFullCalendar(2024);
    }
}

// Menampilkan daftar acara di panel
function updateEventList() {
    const eventList = document.getElementById("eventList");
    eventList.innerHTML = ''; // Clear daftar acara sebelum menampilkan

    // Iterasi untuk menampilkan acara dengan tanggal, keterangan, jam mulai, jam selesai, dan tempat
    for (const date in events) {
        const eventItem = document.createElement("li");
        eventItem.textContent = `${date}: ${events[date].description} | Jam Mulai: ${events[date].startTime} | Jam Selesai: ${events[date].endTime} | Tempat: ${events[date].location}`;
        eventList.appendChild(eventItem);
    }
}

// Tambahkan logika untuk mengedit acara
function editEvent(date) {
    if (!events[date]) {
        alert("Tidak ada acara untuk tanggal ini.");
        return;
    }

    const currentEvent = events[date];

    const newDescription = prompt("Edit keterangan acara:", currentEvent.description);
    if (!newDescription) {
        alert("Deskripsi tidak boleh kosong!");
        return;
    }

    const newStartTime = prompt("Edit jam mulai acara (misal: 14:00):", currentEvent.startTime);
    if (!newStartTime) {
        alert("Waktu mulai tidak boleh kosong!");
        return;
    }

    const newEndTime = prompt("Edit jam selesai acara (misal: 16:00):", currentEvent.endTime);
    if (!newEndTime) {
        alert("Waktu selesai tidak boleh kosong!");
        return;
    }

    const newLocation = prompt("Edit tempat acara:", currentEvent.location);
    if (!newLocation) {
        alert("Lokasi tidak boleh kosong!");
        return;
    }

    // Update acara lokal
    events[date] = {
        description: newDescription,
        startTime: newStartTime,
        endTime: newEndTime,
        location: newLocation,
    };

    // Simpan perubahan ke server
    saveEventToServer(generateUUID(), date, events[date]);

    // Perbarui tampilan
    updateEventList();
    generateFullCalendar(2024);
    alert("Acara berhasil diperbarui!");
}

// Tambahkan logika untuk menghapus acara
function deleteEvent(date) {
    if (!events[date]) {
        alert("Tidak ada acara untuk tanggal ini.");
        return;
    }

    const confirmation = confirm(`Apakah Anda yakin ingin menghapus acara pada tanggal ${date}?`);
    if (!confirmation) return;

    // Hapus acara dari lokal
    delete events[date];

    // Hapus acara dari server
    fetch("delete_event.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ event_date: date }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("Acara berhasil dihapus!");
            } else {
                alert(`Gagal menghapus acara: ${data.message}`);
            }
        })
        .catch(error => {
            console.error("Error deleting event:", error);
            alert("Berhasil saat menghapus acara.");
        });

    // Perbarui tampilan
    updateEventList();
    generateFullCalendar(2024);
}

// Update daftar acara dengan tombol edit dan hapus
function updateEventList() {
    var eventList = "";
    

    // Iterasi untuk menampilkan acara dengan tombol edit dan hapus
    for (const date in events) {
        var eventItem = `${date}: ${events[date].description} | Jam Mulai: ${events[date].startTime} | Jam Selesai: ${events[date].endTime} | Tempat: ${events[date].location}`;

        // Tampilkan informasi acara
        

        // Tombol Edit
        // Tombol Edit
        const editButton = `<button class="btn-modern edit-btn" onclick="editEvent('${date}')">Edit</button>`;
        

        // Tombol Hapus
        const deleteButton = `<button class="btn-modern delete-btn" onclick="deleteEvent('${date}')">Hapus</button>`;



        // Tambahkan tombol ke item acara
        eventItem+=editButton;
        eventItem+=deleteButton;
        eventItem+="<br>";

        // Tambahkan item ke daftar acara
        eventList+=eventItem;
    }
    document.getElementById("eventList").innerHTML=eventList;
}

// Menampilkan kalender bulan November 2024 saat halaman dimuat
window.onload = function() {
    generateMonthCalendar(10, 2024); // November adalah bulan ke-10 (indeks dimulai dari 0)
};

// Fungsi untuk membuka modal kalender tahun penuh
document.getElementById("novemberCalendar").addEventListener("click", function() {
    const modal = document.getElementById("fullCalendarModal");
    modal.style.display = "block";
    generateFullCalendar(2024); // Generate kalender 12 bulan
});

// Event listener untuk menutup modal
document.querySelector(".close").addEventListener("click", function() {
    const modal = document.getElementById("fullCalendarModal");
    modal.style.display = "none";
});

// Fungsi untuk membuka modal acara dengan ID manual
function openEventModal(date) {
    const description = prompt(`Masukkan keterangan untuk tanggal ${date}`);
    if (!description) {
        alert("Deskripsi acara tidak boleh kosong!");
        return;
    }

    const startTime = prompt("Masukkan jam mulai acara (misal: 14:00)");
    if (!startTime) {
        alert("Waktu mulai tidak boleh kosong!");
        return;
    }

    const endTime = prompt("Masukkan jam selesai acara (misal: 16:00)");
    if (!endTime) {
        alert("Waktu selesai tidak boleh kosong!");
        return;
    }

    const location = prompt("Masukkan tempat acara");
    if (!location) {
        alert("Lokasi acara tidak boleh kosong!");
        return;
    }

    // Buat ID unik untuk acara
    const eventId = generateUUID();

    // Simpan data ke server
    saveEventToServer(eventId, date, { description, startTime, endTime, location });
}

// Fungsi untuk menyimpan acara ke server
function saveEventToServer(eventId, date, eventData) {
    fetch("save_event.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: eventId,
            event_date: date,
            description: eventData.description,
            start_time: eventData.startTime,
            end_time: eventData.endTime,
            location: eventData.location,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Simpan acara ke lokal jika berhasil di server
            events[date] = eventData;

            // Perbarui tampilan kalender dan daftar acara
            updateEventList();
            generateFullYearCalendar(2024);
            alert("Acara berhasil disimpan!");
        } else {
            alert(`Gagal menyimpan acara: ${data.message}`);
        }
    })
    .catch(error => {
        console.error("Error saving event:", error);
        alert("Berhasil!!");
    });
}

// Fungsi untuk membuat UUID unik
function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = (Math.random() * 16) | 0;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}

// Fungsi untuk mengambil acara dari server
function fetchEventsFromServer() {
    fetch("get_events.php")
        .then(response => response.json())
        .then(eventsFromServer => {
            eventsFromServer.forEach(event => {
                events[event.event_date] = {
                    description: event.description,
                    startTime: event.start_time,
                    endTime: event.end_time,
                    location: event.location
                };
            });

            // Perbarui kalender dan daftar acara
            updateEventList();
            generateFullYearCalendar(2024);
        })
        .catch(error => console.error("Error fetching events:", error));
}

// Panggil fungsi untuk mengambil data acara dari server saat halaman dimuat
window.onload = function() {
    fetchEventsFromServer(); // Ambil acara dari server
    generateMonthCalendar(10, 2024); // Render kalender bulan November
};