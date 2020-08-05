<?php
$queryString = http_build_query([
  'access_key' => 'faf701dc30d88bbb396fe0a17cdd3548'
]);

$ch = curl_init(sprintf('%s?%s', 'http://api.marketstack.com/v1/eod/2020-07-21', $queryString.'&symbols=AAPL'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json = curl_exec($ch);
curl_close($ch);

$apiResult = json_decode($json, true);
foreach ($apiResult['data'] as $stockData) {
  echo sprintf('On %s, %s has a day high of %s and close of %s', $stockData['date'], $stockData['symbol'], $stockData['high'], $stockData['close'] );
}
?>