// Function to format a number into US Dollars (USD) currency format
export function currencyUSD(value) {
  // Format the value as currency in US Dollars
  const formattedValueUSD = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'}).format(value);
  // Replace the currency symbol '$' with '$ ' to add a space after it
  return formattedValueUSD.replace('$', '$ ');
}

// Function to format a number into British Pounds (GBP) currency format
export function currencyGBP(value) {
  // Format the value as currency in British Pounds
  const formattedValueGBP = new Intl.NumberFormat('en-GB', {style: 'currency', currency: 'GBP'}).format(value);
  // Replace the currency symbol '£' with '£ ' to add a space after it
  return formattedValueGBP.replace('£', '£ ');
}
  
