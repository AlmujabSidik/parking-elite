<div class="text-left border border-gray-100 rounded p-4" x-data="departureInput">
    <h2 class="font-semibold text-lg mb-2">Departing Spain (Returning your vehicle to us)</h2>
    <!-- Has Departure Booking -->
    <div class="mb-4">
        <label for="has_departure_booking" class="block text-sm font-medium text-gray-700 mb-1">Do you have a flight or trip booked Yes or No ?</label>
        <select id="has_departure_booking" name="has_departure_booking"  x-model="hasDepartureBooking" @change="logHasDepartureBooking"
                class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <!-- Departure Meeting Time -->
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
                        onChange: function(selectedDates, dateStr) {
                            if (selectedDates[0]) {
                                // Convert to UTC
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
            <label for="departure_meeting_time" class="flex-grow block font-medium text-sm text-gray-700 mb-1">Meeting Time at Departures Only (NOT TAKE OFF TIME)</label>
            <div class="flex align-middle align-content-center">
                <input
                    x-ref="datetime"
                    type="text"
                    id="departure_meeting_time"
                    data-input
                    placeholder="Select a time"
                    x-bind:disabled="hasDepartureBooking === 'No'"
                    x-bind:class="{
                   'flex w-full h-10 px-3 py-2 text-sm border rounded-md ring-offset-background placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed': true,
                   'bg-white border-neutral-300 focus:border-neutral-300 focus:ring-neutral-400': hasDepartureBooking === 'Yes',
                   'bg-gray-100 border-gray-300': hasDepartureBooking === 'No'
               }"
                >
                <input
                    x-ref="hiddenDatetime"
                    type="hidden"
                    id="departure_meeting_time"
                    name="departure_meeting_time"
                    x-bind:disabled="hasDepartureBooking === 'No'"
                    x-bind:class="{
                   'flex w-full h-10 px-3 py-2 text-sm border rounded-md ring-offset-background placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed': true,
                   'bg-white border-neutral-300 focus:border-neutral-300 focus:ring-neutral-400': hasDepartureBooking === 'Yes',
                   'bg-gray-100 border-gray-300': hasDepartureBooking === 'No'
               }"
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

    <div class="mb-4">
        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-1">Additional Info</label>
        <textarea id="additional_info" name="additional_info" rows="3"
                  class="flex w-full px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Any additional information..."></textarea>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('departureInput', () => ({
            hasDepartureBooking: 'Yes',
        }))
    })
</script>
