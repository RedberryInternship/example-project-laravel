<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="https://3dacq.georgiancard.ge/payment/start.wsm" method="GET">
		<input type="text" name="merchant_id" placeholder="merchant_id" value="{{ config('georgian-card-gateway.merchant_id') }}">
		<input type="text" name="page_id" placeholder="page_id" value="{{ config('georgian-card-gateway.page_id') }}">
		<input type="text" name="account_id" placeholder="account_id" value="{{ config('georgian-card-gateway.account_id') }}">
		<input type="text" name="back_url_f" placeholder="back_url_f" value="{{ config('georgian-card-gateway.back_url_f') }}">
		<input type="text" name="back_url_s" placeholder="back_url_s" value="{{ config('georgian-card-gateway.back_url_s') }}">
		<input type="text" name="ccy" placeholder="ccy" value="{{ config('georgian-card-gateway.ccy') }}">
		<input type="text" name="o.amount" placeholder="amount">
		<input type="text" name="o.order_id" placeholder="order_id" value="test123">
		<input type="submit">
	</form>
</body>
</html>