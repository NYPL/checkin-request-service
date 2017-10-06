<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_auth",
 *   type="oauth2",
 *   authorizationUrl="http://isso.nypl.org/oauth/authorize",
 *   tokenUrl="http://isso.nypl.org/oauth/token",
 *   flow="accessCode",
 *   scopes={
 *     "openid offline_access api": "General API access",
 *     "openid offline_access api patron:read": "Patron specific API access",
 *     "openid offline_access api staff:read": "Staff specific API access"
 *   }
 * )
 */
