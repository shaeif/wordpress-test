#!/usr/bin/env node
/**
 * Rebuilds the Office Wi-Fi Checklist PDF from its HTML source.
 *
 *   npm install playwright   (once, anywhere on your machine)
 *   node bin/build-checklist.js
 *
 * Source: resources/checklist/office-wifi-checklist.html
 * Output: wp-content/plugins/signal-shield-core/downloads/office-wifi-checklist.pdf
 */
const path = require( 'path' );
const { chromium } = require( 'playwright' );

const root = path.resolve( __dirname, '..' );
const source = path.join( root, 'resources/checklist/office-wifi-checklist.html' );
const output = path.join( root, 'wp-content/plugins/signal-shield-core/downloads/office-wifi-checklist.pdf' );

( async () => {
	const browser = await chromium.launch( process.env.CHROMIUM_PATH ? { executablePath: process.env.CHROMIUM_PATH } : {} );
	const page = await browser.newPage();
	await page.goto( 'file://' + source, { waitUntil: 'networkidle' } );
	await page.evaluate( () => document.fonts.ready );
	await page.pdf( { path: output, format: 'A4', printBackground: true, preferCSSPageSize: true } );
	await browser.close();
	console.log( 'Wrote ' + path.relative( root, output ) );
} )();
