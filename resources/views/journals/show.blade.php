<x-app-layout>
    <div class="container py-4">
        <h2>Journal Entry {{ $journal->entry_number }}</h2>
        <p><strong>Date:</strong> {{ $journal->entry_date }}</p>
        <p><strong>Description:</strong> {{ $journal->description }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($journal->total_amount, 0, ',', '.') }}</p>

        <h5>Details</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($journal->details as $d)
                    <tr>
                        <td>{{ $d->account->account_number }} - {{ $d->account->account_name }}</td>
                        <td>{{ number_format($d->debit_amount, 0, ',', '.') }}</td>
                        <td>{{ number_format($d->credit_amount, 0, ',', '.') }}</td>
                        <td>{{ $d->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
