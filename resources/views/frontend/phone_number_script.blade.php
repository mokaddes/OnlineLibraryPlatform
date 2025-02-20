<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
    var input = document.querySelector("#phone");
    var iti;
    var countryCode = '{{ old('country_code',getUserLocation()->country ?? 'NG')}}'

    // Function to set the initial country based on user's location
    function setInitialCountry() {

        iti = window.intlTelInput(input, {
            initialCountry: countryCode,
            autoPlaceholder: "aggressive",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.0/js/utils.js",
            separateDialCode: true,
        });

        getCountryData();
    }

    // Function to get and update country data
    function getCountryData() {
        var selectedCountryData = iti.getSelectedCountryData();
        var dialCode = selectedCountryData.dialCode;
        var countryCode = selectedCountryData.iso2;
        var fullCountryName = selectedCountryData.name;
        // Update your input fields or perform any other actions with the country data
        document.querySelector("#country_name").value = fullCountryName.split(' (')[0];
        document.querySelector("#dialCode").value = dialCode;
        document.querySelector("#country_code").value = countryCode;
    }

    // Call the function to set the initial country
    setInitialCountry();

    input.addEventListener('countrychange', function () {
        getCountryData();
    });
</script>
