function handleInboxModalClick(event, messageId) {
    // Prevent default button behavior (following href="#")
    event.preventDefault();

    var view_update_url = $("#view_update_route").val();
    
    // Perform AJAX request
    // Replace this with your actual AJAX code
    // Example:
    $.ajax({
        url: view_update_url,
        method: 'POST',
        data: { id: messageId },
        success: function(response) {
            // Handle success response
            console.log(response);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(error);
        }
    });
}