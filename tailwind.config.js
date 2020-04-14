const extSpacing = {
  '1/2': '50%',
  '1/3': '33%',
  '2/3': '66%',
  '1/4': '25%',
  '2/4': '50%',
  '3/4': '75%',
  '1/5': '20%',
  '2/5': '40%',
  '3/5': '60%',
  '4/6': '80%',
  '1/6': '16%',
  '2/6': '33%',
  '3/6': '49%',
  '4/6': '66%',
  '5/6': '83%',
  '4/6': '80%',
  '1/8': '12.5%',
  '2/8': '25%',
  '3/8': '37.5%',
  '4/8': '50%',
  '4/8': '50%',
  '5/8': '62.5%',
  '6/8': '75%',
  '7/8': '87.5%',
}


module.exports = {
  theme: {
    extend: {
      spacing: extSpacing,
      minWidth: extSpacing,
      maxWidth: extSpacing,
      minHeight: extSpacing,
      maxHeight: extSpacing,
    },
  },
  variants: {},
  plugins: [],
}
