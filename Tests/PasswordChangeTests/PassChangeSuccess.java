package com.example.tests;

import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;
import org.testng.annotations.*;
import static org.testng.Assert.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class PassChangeSuccess {
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
  public void testPassChangeSuccess() throws Exception {
    driver.get("http://localhost/NotifyMe/Index.php");
    driver.findElement(By.id("emailInput")).click();
    driver.findElement(By.id("emailInput")).clear();
    driver.findElement(By.id("emailInput")).sendKeys("Matthew Manning");
    driver.findElement(By.id("loginInput")).click();
    driver.findElement(By.id("linkToPassChange")).click();
    driver.findElement(By.id("newPassInput")).click();
    driver.findElement(By.id("newPassInput")).clear();
    driver.findElement(By.id("newPassInput")).sendKeys("asdf");
    driver.findElement(By.id("newPassInput2")).click();
    driver.findElement(By.id("newPassInput2")).clear();
    driver.findElement(By.id("newPassInput2")).sendKeys("asdf");
    driver.findElement(By.id("changePassInput")).click();
    driver.findElement(By.id("linkToHomepage")).click();
    driver.findElement(By.id("linkToLogout")).click();
    driver.findElement(By.id("linkToIndex")).click();
    driver.findElement(By.id("emailInput")).click();
    driver.findElement(By.id("emailInput")).clear();
    driver.findElement(By.id("emailInput")).sendKeys("Matthew Manning");
    driver.findElement(By.id("passInput")).click();
    driver.findElement(By.id("passInput")).clear();
    driver.findElement(By.id("passInput")).sendKeys("asdf");
    driver.findElement(By.id("loginInput")).click();
    driver.findElement(By.id("linkToPassChange")).click();
    driver.findElement(By.id("oldPassInput")).click();
    driver.findElement(By.id("oldPassInput")).clear();
    driver.findElement(By.id("oldPassInput")).sendKeys("asdf");
    driver.findElement(By.id("changePassInput")).click();
    driver.findElement(By.id("linkToHomepage")).click();
    driver.findElement(By.id("linkToLogout")).click();
    driver.findElement(By.id("linkToIndex")).click();
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
