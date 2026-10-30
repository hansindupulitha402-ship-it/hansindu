# LIGOZZA payment setup

The site now includes a checkout flow with:
- Cash on Delivery (fully usable as a front-end order flow)
- PayHere online payment option (setup-ready)

For a real PayHere payment, use a server-side integration. PayHere requires a Merchant ID, a Merchant Secret, a public notify URL, and verification of the payment notification checksum. Never put the Merchant Secret in browser JavaScript.

Official documentation: https://support.payhere.lk/api-%26-mobile-sdk/checkout-api
