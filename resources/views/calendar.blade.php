<x-layout>
    <div class="relative top-0 bottom-0 right-0 flex-shrink-0 hidden w-1/3 overflow-hidden bg-cover lg:block">
        <div class="absolute inset-0 z-20 w-full h-full opacity-70 bg-gradient-to-t from-black"></div>
        <img src="https://elitecarparking.es/wp-content/uploads/2024/09/car-park.png" class="z-10 object-cover w-full h-full" />
    </div>
    <div class="relative flex flex-col flex-wrap items-center w-full h-full px-8">
        <div x-data="formBooking" class="relative w-full max-w-lg mx-auto py-16 lg:mb-0">
            <img src="{{ asset('/logo-comp.webp') }}" alt="Logo Elite Car Parking" class="w-16 mb-6" />
            <div x-show="isShowForm" class="relative">
                <div class="flex flex-col mb-6 space-y-2">
                    <h1 class="text-2xl font-semibold tracking-tight">Contract Clients</h1>
                    <p class="text-sm text-neutral-500">Booking Form! Please complete the form below with ALL
                        information to make arrangements with us.</p>
                </div>
                <x-form-booking />
                <p class="px-8 mt-1 text-sm text-center text-neutral-500">By submitting this booking form you agree that you have read & accept the terms & privacy policies.</p>
                <hr class="m-10" />
            </div>

            <x-office-hours />

        </div>
    </div>
</x-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formBooking', () => ({
            isShowForm: false,

            init() {
                this.checkOfficeHours();
                setInterval(() => this.checkOfficeHours(), 60000);
            },

            checkOfficeHours() {
                const now = new Date();
                const day = now.getDay(); // 0 is Sunday, 6 is Saturday
                const hour = now.getHours();
                const minute = now.getMinutes();

                // Convert current time to minutes since midnight
                const currentTime = hour * 60 + minute;

                // Check if it's between Dec 21 and Dec 28
                const month = now.getMonth(); // 0-indexed, so 11 is December
                const date = now.getDate();
                if (month === 11 && date >= 21 && date <= 28) {
                    this.isShowForm = false;
                    return;
                }

                // Check weekday hours (Monday to Friday)
                if (day >= 1 && day <= 5) {
                    this.isShowForm = (currentTime >= 9 * 60 && currentTime < 18 * 60);
                }
                // Check weekend hours (Saturday and Sunday)
                else {
                    this.isShowForm = (currentTime >= 9 * 60 && currentTime < 24 * 60);
                }
            }
        }))
    });
</script>
