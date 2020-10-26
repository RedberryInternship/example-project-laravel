import { listen } from './listeners/listener'
import { fetchAllTheData } from './data/fetch-all-data'

window.onload = () => {
  fetchAllTheData();
  listen();
};
