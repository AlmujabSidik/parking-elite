<div class="text-left border border-gray-100 rounded p-4" x-data="arrivalInput">
    <h2 class="font-semibold text-lg mb-2">Arriving in Spain (Collecting your vehicle from us)</h2>
    <!-- Has Arrival Booking -->
    <div class="mb-4">
        <label for="has_arrival_booking" class="block text-sm font-medium text-gray-700 mb-1">Do you have a flight or trip
            booked Yes or No ?</label>
        <select id="has_arrival_booking" name="has_arrival_booking" x-model="hasArrivalBooking"
                @change="logHasArrivalBooking"
                class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <!-- Arrival Mode -->
    <div x-show="hasArrivalBooking === 'Yes'">
        <div class="mb-4">
            <label for="arrival_mode" class="block text-sm font-medium text-gray-700 mb-1">Flight Number or "By Car" or
                "By Train" etc</label>
            <input type="text" id="arrival_mode" name="arrival_mode"
                   x-bind:disabled="hasArrivalBooking === 'No'"
                   class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                   placeholder="Flight/Car/Train">
        </div>

        <!-- Arrival Date and Time -->
        <h3>Date & Actual Due Time of Arrival</h3>
        <div class="mb-4">
            <div
                x-data="{ formattedDate: '' }"
                x-init="
                flatpickr($refs.datetimewidget, {
                    wrap: true,
                    enableTime: true,
                    dateFormat: 'M j, Y H:i',
                    time_24hr: true,
                    minDate: new Date().fp_incr(1),
                    minuteIncrement: 5,
                    disableMobile: true, // Menonaktifkan picker native mobile
                    onChange: function(selectedDates, dateStr) {
                        if (selectedDates[0]) {
                            const utcDate = new Date(selectedDates[0].getTime() - selectedDates[0].getTimezoneOffset() * 60000);
                            formattedDate = utcDate.toISOString().slice(0, 16);
                            $refs.hiddenDatetime.value = formattedDate;
                        }
                    }
                });
            "
                x-ref="datetimewidget"
                class="flatpickr container mx-auto col-span-6 sm:col-span-6 mt-5"
            >
                <label for="arrival_datetime_display" class="flex-grow block font-medium text-sm text-gray-700 mb-1">Please Do Not "Add" Extra Time to the scheduled landing</label>
                <div class="flex align-middle align-content-center">
                    <input
                        x-ref="datetime"
                        type="text"
                        id="arrival_datetime_display"
                        data-input
                        placeholder="Select a time"
                        class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                    <input
                        x-ref="hiddenDatetime"
                        type="hidden"
                        id="arrival_datetime"
                        name="arrival_datetime"
                    >
                    <a
                        class="h-11 w-10 input-button cursor-pointer rounded-r-md bg-transparent border-gray-300 border-t border-b border-r"
                        title="clear" data-clear
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-2 ml-1" viewBox="0 0 20 20" fill="#c53030">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Has Hold Luggage -->
        <div class="mb-4">
            <label for="has_hold_luggage" class="block text-sm font-medium text-gray-700 mb-1">Do you have any Hold
                Luggage?</label>
            <select id="has_hold_luggage" name="has_hold_luggage"
                    class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>
</div>

<style>
    /* CSS kustom untuk memperbaiki tampilan pada mobile */
    @media (max-width: 768px) {
        .flatpickr-calendar {
            font-size: 14px;
        }
        .flatpickr-current-month {
            font-size: 1.2em;
        }
        .flatpickr-day {
            line-height: 34px;
            height: 34px;
        }
        .flatpickr-time input {
            font-size: 14px;
        }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('arrivalInput', () => ({
            hasArrivalBooking: 'Yes',
            arrivalMode: '',
            arrivalDateTime: '',
        }))
    })
</script>
