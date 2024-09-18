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
            <label for="arrival_datetime" class="block text-sm font-medium text-red-700 mb-1">Please Do Not "Add" Extra
                Time to the scheduled landing</label>
            <input type="datetime-local" id="arrival_datetime" name="arrival_datetime"
                min="{{ now()->format('Y-m-d\TH:i') }}"
                class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('arrivalInput', () => ({
            hasArrivalBooking: 'Yes',
            arrivalMode: '',
            arrivalDateTime: '',

        }))
    })
</script>
