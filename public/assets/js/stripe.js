console.log('start stripe');
// Create an instance of the Stripe object with your publishable API key
    var stripe = Stripe("pk_test_51IQaYSCmsFH1G20FBuqkKwIfi2WLoWOAUx2nzPPX9kKktRIhDIiVJzF91dk6qHgRnKedliBOeQfhmBidw9DlEtlc00XcqlT5mT");
    var checkoutButton = document.getElementById("checkout-button");
    checkoutButton.addEventListener("click", function () {
        fetch("/commande/create-session", {
            method: "POST",
        })
        .then(function (response) {
          return response.json();
        })
        .then(function (session) {
          return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function (result) {
          // If redirectToCheckout fails due to a browser or network
          // error, you should display the localized error message to your
          // customer using error.message.
          if (result.error) {
            alert(result.error.message);
          }
        })
        .catch(function (error) {
          console.error("Error:", error);
        });
    });