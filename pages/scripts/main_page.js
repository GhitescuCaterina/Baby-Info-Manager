const calendarEl = document.getElementById('calendar');
const monthNameEl = document.getElementById('month-name');
const prevBtn = document.getElementById('prev-btn');
const nextBtn = document.getElementById('next-btn');
const tableEl = document.getElementById('calendar');

const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

let currentMonth = moment().startOf('month');

function renderCalendar(month) {
  // Set the month name header
  monthNameEl.innerText = month.format('MMMM YYYY');

  // Clear the table
  tableEl.innerHTML = '';

  // Create a row for the day names
  const dayNamesRow = document.createElement('tr');
  tableEl.appendChild(dayNamesRow);

  // Add the day names to the row
  for (const dayName of dayNames) {
    const dayNameCell = document.createElement('th');
    dayNameCell.innerText = dayName;
    dayNamesRow.appendChild(dayNameCell);
  }

  // Re-order the day names to start with Monday
  dayNamesRow.insertBefore(dayNamesRow.lastChild, dayNamesRow.firstChild);

  // Create a row for the dates
  let row = document.createElement('tr');
  tableEl.appendChild(row);

  // Add empty cells for the first day of the month
  const firstDayOfMonth = month.startOf('month').day();
  for (let i = 0; i < firstDayOfMonth; i++) {
    const cell = document.createElement('td');
    row.appendChild(cell);
  }

  // Add cells for each day of the month
  const numDaysInMonth = month.daysInMonth();
  for (let i = 1; i <= numDaysInMonth; i++) {
    // Start a new row after Sunday
    if (row.children.length === 7) {
      row = document.createElement('tr');
      tableEl.appendChild(row);
    }

    // Create a new cell for the day
    const cell = document.createElement('td');
    cell.innerText = i;

    // Add a class to the cell if it's the current day
    if (month.isSame(moment(), 'month') && i === moment().date()) {
      cell.classList.add('today');
    }

    row.appendChild(cell);
  }

  // Add empty cells for the last day of the month
  const lastDayOfMonth = month.endOf('month').day();
  for (let i = lastDayOfMonth; i < 6; i++) {
    const cell = document.createElement('td');
    row.appendChild(cell);
  }
}

// Render the initial calendar
renderCalendar(currentMonth);

// Go to the previous month when the previous button is clicked
prevBtn.addEventListener('click', () => {
  currentMonth = currentMonth.subtract(1, 'month');
  renderCalendar(currentMonth);
});

// Go to the next month when the next button is clicked
nextBtn.addEventListener('click', () => {
  currentMonth = currentMonth.add(1, 'month');
  renderCalendar(currentMonth);
});
