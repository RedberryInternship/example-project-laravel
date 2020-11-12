import { listen } from './listeners/listener'
import { fetchAllTheData } from './data/fetch-all-data'
import { renderWhitelistModal } from './UI/renderer'

window.onload = async () => {
  await fetchAllTheData();
  renderWhitelistModal();
  listen();
};
