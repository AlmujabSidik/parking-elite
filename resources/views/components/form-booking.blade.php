<form action="{{ route('calendar.store') }}" method="POST" x-data="formHandler()" @submit.prevent="submitForm" class="space-y-2">
    @csrf
    <x-required-input />
    <x-arriving-input />
    <x-departure-input />

    <div x-show="hasErrors" class="text-red-500 text-sm mt-2">
        <template x-for="(errorMessages, field) in errors" :key="field">
            <div>
                <template x-for="(error, index) in errorMessages" :key="index">
                    <p x-text="error"></p>
                </template>
            </div>
        </template>
    </div>
    <button type="submit" :disabled="isSubmitting" class="inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">
        <span x-show="!isSubmitting">Book it</span>
        <span x-show="isSubmitting">Processing booking...</span>
    </button>
</form>

<script>
    function formHandler() {
        return {
            isSubmitting: false,
            errors: {},
            hasErrors: false,
            async submitForm() {
                this.isSubmitting = true;
                this.errors = {};
                this.hasErrors = false;

                try {
                    const response = await fetch(this.$el.action, {
                        method: 'POST',
                        body: new FormData(this.$el),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            this.errors = result.errors;
                            this.hasErrors = true;
                        } else {
                            throw new Error(result.message || 'An error occurred');
                        }
                    } else if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Thank you for booking. Please check your email for confirmation details.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$el.reset();
                            }
                        });
                    } else {
                        throw new Error(result.message || 'An unexpected error occurred');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || "An error occurred. Please try again.",
                        confirmButtonText: 'OK'
                    });
                } finally {
                    this.isSubmitting = false;
                }
            }
        }
    }
</script>
