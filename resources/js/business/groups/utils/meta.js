import { toInteger } from "lodash";

/**
 * Get laravel csrf token.
 * 
 * @returns {string}
 */
export const getCSRF = () => {
  return document.querySelector('meta[name="_token"]').content;
}

/**
 * Get group id.
 * 
 * @returns {BigInteger}
 */
export const getGroupId = () => {
  const groupId = document.querySelector('meta[name="group_id"]').content;
  return +groupId;
}