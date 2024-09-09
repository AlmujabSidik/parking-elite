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

        <label for="departure_meeting_time" class="block text-sm font-medium text-gray-700 mb-1">Meeting Time at Departures Only (NOT TAKE OFF TIME)</label>
        <input type="datetime-local"  id="departure_meeting_time" name="departure_meeting_time" min="{{ now()->format('Y-m-d\TH:i') }}"
               x-bind:disabled="hasDepartureBooking === 'No'"
               x-bind:class="{
                   'flex w-full h-10 px-3 py-2 text-sm border rounded-md ring-offset-background placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed': true,
                   'bg-white border-neutral-300 focus:border-neutral-300 focus:ring-neutral-400': hasDepartureBooking === 'Yes',
                   'bg-gray-100 border-gray-300': hasDepartureBooking === 'No'
               }">
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
            logHasDepartureBooking() {
                console.log(this.hasDepartureBooking)
            }
        }))
    })
</script>
