import { state } from './state'
import { 
  getChargerWhitelist,
  getPhoneCodes,
 } from '../utils/service'

/**
 * Fetch all the necessary data
 * from server and set into the global state.
 * 
 * @returns {Promise<void>}
 */
export const fetchAllTheData = async () => {
  await fetchChargerWhitelistData();
  await fetchPhoneCodes();

  console.log(state);
}

/**
 * Get charger whitelist and set its data
 * into global state.
 * 
 * @returns {Promise<void>}
 */
export const fetchChargerWhitelistData = async () => {
  try {
    const result = await getChargerWhitelist();
    const data = await result.json();

    state.whitelist = data;
  }
  catch(e) {
    console.log(e);
  }
}

/**
 * Get fetch phone codes data and
 * set it into global state.
 * 
 * @returns {Promise<void>}
 */
const fetchPhoneCodes = async () => {
  try {
    const result = await getPhoneCodes();
    const { data } = await result.json();

    state.phoneNumbers = data;
  }
  catch(e) {
    console.log(e);
  }
}
