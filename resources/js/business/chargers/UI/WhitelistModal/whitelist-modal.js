import { phoneCodesSelect } from './components/select'
import whitelistContent from './components/whitelist-content'

/**
 * Generate country codes modal.
 * 
 * @returns {string}
 */
export default () => {
  const select = phoneCodesSelect();

  return `
  <div class="whitelist-modal-bg">
    <div class="whitelist-modal-wrapper">
      <img class="whitelist-modal-close-btn" src="/images/simulator/close.png" />
      <div class="row">
        ${select}
        <div class="col s3 whitelist-input-wrapper">
          <input class="whitelist-input bpg-arial" placeholder="5XX XX XX XX"/>
          <span class="whitelist-input-mistake hide"></span>
        </div>
        <div class="col s5 push-s2 btn add-whitelist-button bpg-arial">დაამატე ნომერი ვაითლისტში</div>
      </div>
      <ul class="collection whitelist-modal">
        ${whitelistContent()}
      </ul>
    </div>
  </div>
  `;
}

