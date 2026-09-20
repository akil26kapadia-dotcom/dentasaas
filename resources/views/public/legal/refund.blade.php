@php $days = (int) config('dentasaas.business.refund_days', 7); @endphp
<x-legal-shell title="Refund and Cancellation Policy" path="/refund-policy" updated="20 September 2026"
    description="How cancellation and refunds work for DentaSaaS paid plans: no automatic charges, cancel any time, and a refund window after your first paid period.">

    <p>This policy explains how cancellation and refunds work for DentaSaaS paid plans.</p>

    <h2>The Free plan</h2>
    <p>The Free plan costs nothing, so there is nothing to refund. You can use it for as long as you like.</p>

    <h2>How paid plans are billed</h2>
    <p>Paid plans are paid in advance for the period you choose. We do not charge you automatically: you pay when you decide to start or renew, and your plan is activated once we receive the payment.</p>

    <h2>Cancelling</h2>
    <p>You can stop at any time. Your paid plan stays active until the end of the period you have paid for, and then your clinic moves to the Free plan. You do not need to do anything to prevent further charges because nothing renews automatically. Your existing data is kept.</p>

    <h2>Refunds</h2>
    <ul>
        <li><strong>First paid period:</strong> if you upgrade from the Free plan and are not satisfied, you can ask for a full refund within {{ $days }} days of your payment.</li>
        <li><strong>After that:</strong> payments for a period already under way are not refundable, and we do not give partial refunds for unused time when you cancel or downgrade.</li>
        <li><strong>Mistakes:</strong> if you were charged twice or in the wrong amount, we will refund the difference in full.</li>
    </ul>

    <h2>How to ask for a refund</h2>
    <p>Message us on WhatsApp or email us with your clinic name and the payment reference. We will confirm the request within a few working days and return the money to the account it came from.</p>
</x-legal-shell>
