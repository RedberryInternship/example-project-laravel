<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="https://3dacq.georgiancard.ge/payment/start.wsm" method="GET">
		<input type="text" name="merchant_id" placeholder="merchant_id" value="{{ config('georgian-card-gateway.merchant_id') }}">
		<input type="text" name="page_id" placeholder="page_id" value="{{ config('georgian-card-gateway.page_id') }}">
		<input type="text" name="account_id_gel" placeholder="account_id_gel" value="{{ config('georgian-card-gateway.account_id_gel') }}">
		<input type="text" name="ccy" placeholder="ccy" value="{{ config('georgian-card-gateway.ccy') }}">
		<input type="text" name="amount" placeholder="amount">
		<input type="submit" name="submit">
	</form>
</body>
</html>