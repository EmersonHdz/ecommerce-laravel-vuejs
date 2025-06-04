<x-app-layout>
    <div class="w-[400px] mx-auto bg-red-500 py-4 px-6 text-white rounded shadow-lg mt-10 text-center">
        <h1 class="text-2xl font-bold mb-2">Oops! Payment Failed</h1>
        <p class="text-sm">{{$message ?? 'There was a problem processing your payment. Please try again.'}}</p>
    </div>

    {{-- SweetAlert2 --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Payment Error',
                text: @json($message ?? 'There was a problem processing your payment.'),
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        });
    </script>
</x-app-layout>
