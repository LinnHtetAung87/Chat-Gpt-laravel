<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat GPT Laravel | Code with Linn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="./image/download.png"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/style.css">

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
<div class="chat">

  <!-- Header -->
  <div class="top">
    <img src="./image/photo.jpeg" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%;">
    <div>
      <p>Mr.Linn</p>
      <small>Online</small>
    </div>
  </div>

  <!-- Chat Window -->
  <div class="messages" style="overflow-y:auto; height: 400px;">
    <div class="left message">
      <img src="./image/photo.jpeg" alt="Bot Avatar">
      <p>Start chatting with Chat GPT AI below!!</p>
    </div>
  </div>

  <!-- Footer -->
  <div class="bottom">
    <form id="chat-form">
      <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
      <button type="submit"></button>
    </form>
  </div>

</div>

<script>
  $(function () {
    const messageBox = $(".messages");

    $("#chat-form").submit(function (event) {
      event.preventDefault();

      const message = $("#message").val().trim();
      if (message === '') return;

      // Disable form
      $("#message").prop('disabled', true);
      $("#chat-form button").prop('disabled', true);

      // Show user's message
      messageBox.append(`
        <div class="right message">
          <p>${message}</p>
          <img src="./image/photo.jpeg" alt="User Avatar">
        </div>
      `);

      $.ajax({
        url: "/chat",
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          "content": $("form #message").val()
        }
      }).done(function (res) {
        // Show AI's reply
        messageBox.append(`
          <div class="left message">
            <img src="./image/download.jpeg" alt="Bot Avatar">
            <p>${res}</p>
          </div>
        `);

        // Clear and re-enable input
        $("#message").val('');
        $("#message").prop('disabled', false);
        $("#chat-form button").prop('disabled', false);

        // Auto-scroll to bottom
        messageBox.scrollTop(messageBox[0].scrollHeight);
      });
    });
  });
</script>

</body>
</html>
