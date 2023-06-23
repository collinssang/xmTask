function validateForm() {
    // Reset error messages
    document.getElementById('errorMessages').innerHTML = '';

    // Retrieve form data
    var companySymbol = document.forms['stockForm']['companySymbol'].value;
    var startDate = document.forms['stockForm']['startDate'].value;
    var endDate = document.forms['stockForm']['endDate'].value;
    var email = document.forms['stockForm']['email'].value;

    // Perform client-side validation
    var errors = [];

    if (companySymbol === '') {
        errors.push('Company Symbol is required.');
    }

    if (startDate === '') {
        errors.push('Start Date is required.');
    } else if (!isValidDate(startDate)) {
        errors.push('Start Date is not a valid date.');
    } else {
        var currentDate = new Date().toISOString().split('T')[0];
        if (startDate > currentDate) {
            errors.push('Start Date cannot be greater than the current date.');
        }
    }

    if (endDate === '') {
        errors.push('End Date is required.');
    } else if (!isValidDate(endDate)) {
        errors.push('End Date is not a valid date.');
    } else {
        var currentDate = new Date().toISOString().split('T')[0];
        if (endDate < currentDate) {
            errors.push('End Date cannot be greater than the current date.');
        }

        if (startDate > endDate) {
            errors.push('End Date must be greater than or equal to Start Date.');
        }
    }

    if (email === '') {
        errors.push('Email is required.');
    } else if (!isValidEmail(email)) {
        errors.push('Email is not valid.');
    }

    // Display client-side validation errors
    if (errors.length > 0) {
        var errorMessages = '';
        for (var i = 0; i < errors.length; i++) {
            errorMessages += '<li>' + errors[i] + '</li>';
        }
        document.getElementById('errorMessages').innerHTML = '<ul>' + errorMessages + '</ul>';
        return false;
    }

    // Server-side validation is not necessary if client-side validation passes
    return true;
}

function isValidDate(dateString) {
    var regexDate = /^\d{4}-\d{2}-\d{2}$/;
    if (!regexDate.test(dateString)) {
        return false;
    }
    var date = new Date(dateString);
    return !isNaN(date.getTime());
}

function isValidEmail(email) {
    var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regexEmail.test(email);
}
