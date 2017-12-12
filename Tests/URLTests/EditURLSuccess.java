package com.example.tests;

import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;
import org.testng.annotations.*;
import static org.testng.Assert.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class EditURLSuccess {
  private WebDriver driver;
  private String baseUrl;
  private boolean acceptNextAlert = true;
  private StringBuffer verificationErrors = new StringBuffer();

  @BeforeClass(alwaysRun = true)
  public void setUp() throws Exception {
    driver = new FirefoxDriver();
    baseUrl = "https://www.katalon.com/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
  }

  @Test
  public void testEditURLSuccess() throws Exception {
    driver.get("http://localhost/NotifyMe/");
    driver.findElement(By.id("emailInput")).click();
    driver.findElement(By.id("emailInput")).clear();
    driver.findElement(By.id("emailInput")).sendKeys("mdmanning@gmail.com");
    driver.findElement(By.id("passInput")).click();
    driver.findElement(By.id("passInput")).clear();
    driver.findElement(By.id("passInput")).sendKeys("asdf");
    driver.findElement(By.id("loginInput")).click();
    driver.findElement(By.xpath("(//a[contains(text(),'Edit')])[2]")).click();
    driver.findElement(By.id("editURLInput")).click();
    driver.findElement(By.id("editURLInput")).clear();
    driver.findElement(By.id("editURLInput")).sendKeys("smogon.com");
    driver.findElement(By.id("editURLSubmitInput")).click();
    driver.findElement(By.id("linkToURL2")).click();
  }

  @AfterClass(alwaysRun = true)
  public void tearDown() throws Exception {
    driver.quit();
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }

  private boolean isElementPresent(By by) {
    try {
      driver.findElement(by);
      return true;
    } catch (NoSuchElementException e) {
      return false;
    }
  }

  private boolean isAlertPresent() {
    try {
      driver.switchTo().alert();
      return true;
    } catch (NoAlertPresentException e) {
      return false;
    }
  }

  private String closeAlertAndGetItsText() {
    try {
      Alert alert = driver.switchTo().alert();
      String alertText = alert.getText();
      if (acceptNextAlert) {
        alert.accept();
      } else {
        alert.dismiss();
      }
      return alertText;
    } finally {
      acceptNextAlert = true;
    }
  }
}
