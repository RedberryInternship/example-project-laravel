import { fromPairs } from "lodash"

export const removableGroups = () => Array.from(document.getElementsByClassName('removable-groups'));
export const alertCard = () => document.querySelector('.warning-alert')