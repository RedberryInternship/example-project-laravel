
/**
 * Default configuration of the fetch api
 */
export const curlDefaultConfig = {
  headers: {
    'Accept': 'application/json',
    'Content-type': 'application/json',
  }
}

/**
 * Add phone number to whitelist mistake messages.
 * 
 * @var {object} mistakeMsg
 */
export const mistakeMsg = {
  georgianMistakeText: '* არასწორი ფორმატი',
  generalMistakeText: '* მინიმუმ 9 სიმბოლოს უნდა შეიცავდეს...'
}