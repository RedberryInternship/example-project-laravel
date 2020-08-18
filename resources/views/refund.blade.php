<html>
  <head>
    <title>Refund Page</title>
  </head>
  <body>
    <form method="POST" action="{{ route('refund') }}">
      @csrf
      <input type="text" name="rrn" placeholder="RRN" /> <br>
      <input type="text" name="trxId" placeholder="trxId" /> <br>
      <input type="text" name="amount" placeholder="Amount" /> <br><br>
      <input type="submit" value="Refund" />
    </form>
  </body>
</html>