<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="https://3dacq.georgiancard.ge/payment/start.wsm" method="GET">
		<input type="text" name="lang" value="KA">
		<input type="text" name="preauth" value="Y">
		<input type="text" name="merch_id" placeholder="merch_id" value="{{ config('georgian-card-gateway.merchant_id') }}">
		<input type="text" name="page_id" placeholder="page_id" value="{{ config('georgian-card-gateway.page_id') }}">
		<input type="text" name="account_id" placeholder="account_id" value="{{ config('georgian-card-gateway.account_id') }}">
		<input type="text" name="back_url_f" placeholder="back_url_f" value="https://api-dev.e-space.ge/failed">
		<input type="text" name="back_url_s" placeholder="back_url_s" value="https://api-dev.e-space.ge/succeed">
		<input type="text" name="ccy" placeholder="ccy" value="{{ config('georgian-card-gateway.ccy') }}">
		<input type="text" name="o.amount" placeholder="amount">
		<input type="text" name="o.order_id" placeholder="order_id" value="test123">
		<input type="submit">
	</form>
</body>
</html>