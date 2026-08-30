/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

function daikinRCCloud_toggleAuthMode() {
  const mode = document.getElementById('daikin_authMode').value
  const isMobile = mode === 'mobile_app'
  document.getElementById('daikin-auth-mobile')?.[isMobile ? 'seen' : 'unseen']()
  document.getElementById('daikin-auth-developer')?.[isMobile ? 'unseen' : 'seen']()
  document.querySelectorAll('.daikin-auth-port').forEach(el => el[isMobile ? 'unseen' : 'seen']())
}

domUtils(function() {
  daikinRCCloud_toggleAuthMode()
  document.getElementById('daikin_authMode')?.registerEvent('change', daikinRCCloud_toggleAuthMode)
})
